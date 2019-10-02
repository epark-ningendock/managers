<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseManagerCommand;
use \GuzzleHttp\Client;

use App\Rail;
use App\Station;
use App\RailwayCompany;
use App\PrefectureRail;
use App\RailStation;

use Log;

/**
 * EkiSpertManager
 * 路線情報更新バッチ
 *
 * @author footbank.co.jp
 * @copyright 株式会社EPARK人間ドック
 * @package EPARK人間ドック
 * @version 20190731
 */
class EkiSpertManagerCommand extends BaseManagerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:EkiSpertManager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '路線情報更新';

    /** 駅すぱーと APIユーザーキー */
    const EKISPERT_API_KEY = 'LE_qjcTJnxRxGvEQ';

    /** 駅すぱーと APIリクエスト元URL */
    const EKISPERT_REF = 'http://www.docknet.jp/';

    /** 駅すぱーと APIサーバーURL */
    const EKISPERT_URL = 'http://api.ekispert.jp/v1/json';

    /** 運行路線情報URL */
    const OPERATION_LINE_URL = self::EKISPERT_URL . '/operationLine?key=';

    /** 駅情報URL */
    const STATION_URL = self::EKISPERT_URL . '/station?key=';

    /** 鉄道会社情報URL */
    const CORPORATION_URL = self::EKISPERT_URL . '/corporation?key=';

    /** API LIMIT */
    const LIMIT = 100;

    /**
     * Execute Method.
     *
     * @return mixed
     */
    public function _execute()
    {
        // 運行路線情報取得
        $all_count = $this->getAllCount(self::OPERATION_LINE_URL);
        $this->updateOperationLine($all_count);

        // 駅情報取得
        $all_count = $this->getAllCount(self::STATION_URL);
        $this->updateStation($all_count);

        // 鉄道会社情報
        $all_count = $this->getAllCount(self::CORPORATION_URL);
        $this->updateCorporation($all_count);

        // 都道府県別路線情報取得
        $this->updatePrefectureRail();

        // 運行路線別駅情報取得
        $this->updateRailStation();

        return 0;
    }

    /**
     * 全件数取得
     *
     * @param  string $url 駅スパートAPI URL
     * @return integer 全件数
     */
    private function getAllCount($url)
    {
        $url = $url . urlencode(self::EKISPERT_API_KEY);
        $client = new Client();
        $response = $client->request('GET', $url);
        $result = $this->getResultSet($response);
        return intval($result->max ?? 0);
    }

    /**
     * 運行路線情報更新
     *
     * @param  integer $all_count 全件数
     * @return integer $count 登録/更新件数
     */
    private function updateOperationLine($all_count)
    {
        $offset = 1;
        $page = intval(ceil($all_count / self::LIMIT));
        $param = '&offset=';

        // statusクリア
        Rail::where('status', '1')->update(['status' => 'X']);
        $count = 0;
        for ($i = 0; $i < $page; $i++) {

            // API request
            $url = self::OPERATION_LINE_URL . urlencode(self::EKISPERT_API_KEY) . $param . $offset;
            $result = $this->getJsonElements($url);

            // 運用会社情報取得
            // 運用会社情報はarrayでない場合がある。
            $corporation = $result->Corporation ?? [];
            // corporationIndexでひっぱりやすいように配列に変換
            $corporation = is_array($result->Corporation) ? $corporation : [$corporation];
            if (empty($corporation))
                throw new \Exception('応答データに Corporation が存在しませんでした。' . PHP_EOL . print_r($result, true));

            // 路線情報取得
            $line = $result->Line ?? [];
            if (empty($line))
                throw new \Exception('応答データに Line が存在しませんでした。' . PHP_EOL . print_r($result, true));

            // 路線マスタ更新
            foreach ($line as $l) {

                $v = new Rail();
                $r = [
                    'es_code' => $l->code,
                    'railway_company_id' => $corporation[$l->corporationIndex - 1]->code,
                    'name' => $l->Name,
                    'status' => 1,
                ];
                if (!$v->validate($r)) {
                    Log::error($v->errors());
                    continue; // 次のレコードへ
                }

                $rail = Rail::firstOrCreate(['es_code' => $l->code], $r);
                if (!$rail->wasRecentlyCreated) { // update(既存レコードあり)
                    $rail->name = $l->Name;
                    $rail->status = 1;
                    $rail->update();
                }
            }

            // 取り損ねの場合処理を抜ける
            if (intval($result->max ?? 0) < self::LIMIT) return intval($result->max ?? 0);

            $offset += self::LIMIT;
            ++$count;
        } // for loop end.

        return $count;
    }

    /**
     * 駅線情報更新
     *
     * @param  integer $all_count 全件数
     * @return integer $count 登録/更新件数
     */
    private function updateStation($all_count)
    {

        $offset = 1;
        $page = intval(ceil($all_count / self::LIMIT));
        $param = '&offset=';

        // statusクリア
        Station::where('status', '1')->update(['status' => 'X']);
        $count = 0;
        for ($i = 0; $i < $page; $i++) {

            // API request
            $url = self::STATION_URL . urlencode(self::EKISPERT_API_KEY) . $param . $offset;
            $result = $this->getJsonElements($url);
            $points = $result->Point ?? [];
            if (empty($points))
                throw new \Exception('応答データに Point が存在しませんでした。' . PHP_EOL . print_r($result, true));

            // 駅情報更新
            foreach ($points as $p) {
                if (!property_exists($p, 'GeoPoint'))
                    throw new \Exception('応答データに GeoPoint が存在しませんでした。' . PHP_EOL . print_r($p, true));

                if (!property_exists($p, 'Prefecture'))
                    throw new \Exception('応答データに Prefecture が存在しませんでした。' . PHP_EOL . print_r($p, true));

                if (!property_exists($p, 'Station'))
                    throw new \Exception('応答データに Station が存在しませんでした。' . PHP_EOL . print_r($p, true));

                $v = new Station();
                $r = [
                    'es_code' => $p->Station->code,
                    'prefecture_id' => $p->Prefecture->code,
                    'name' => $p->Station->Name,
                    'kana' => $p->Station->Yomi,
                    'longitude' => $p->GeoPoint->longi_d,
                    'latitude' => $p->GeoPoint->lati_d,
                    'status' => 1,
                ];
                if (!$v->validate($r)) {
                    Log::error($v->errors());
                    continue; // 次のレコードへ
                }

                $station = Station::firstOrCreate(['es_code' => $p->Station->code], $r);
                if (!$station->wasRecentlyCreated) { // update(既存レコードあり)
                    $station->prefecture_id = $p->Prefecture->code;
                    $station->name = $p->Station->Name;
                    $station->kana = $p->Station->Yomi;
                    $station->longitude = $p->GeoPoint->longi_d;
                    $station->latitude = $p->GeoPoint->lati_d;
                    $station->status = 1;
                    $station->update();
                }
            } // for loop end

            // 取り損ねの場合処理を抜ける
            if (intval($result->max ?? 0) < self::LIMIT) return intval($result->max ?? 0);

            $offset += self::LIMIT;
            ++$count;
        } // for loop end

        return $count;
    }

    /**
     * 鉄道会社情報更新
     *
     * @param  integer $all_count 全件数
     * @return integer $count 登録/更新件数
     */
    private function updateCorporation($all_count)
    {
        $offset = 1;
        $page = intval(ceil($all_count / self::LIMIT));
        $param = '&offset=';

        // statusクリア
        RailwayCompany::where('status', '1')->update(['status' => 'X']);
        $count = 0;
        for ($i = 0; $i < $page; $i++) {

            // API request
            $url = self::CORPORATION_URL . urlencode(self::EKISPERT_API_KEY) . $param . $offset;
            $result = $this->getJsonElements($url);
            $corporations = $result->Corporation ?? [];
            if (empty($corporations))
                throw new \Exception('応答データに Corporation が存在しませんでした。' . PHP_EOL . print_r($result, true));

            // 駅情報更新
            foreach ($corporations as $c) {

                $v = new RailwayCompany();
                $r = [
                    'es_code' => $c->code,
                    'name' => $c->Name,
                    'status' => 1,
                ];
                if (!$v->validate($r)) {
                    Log::error($v->errors());
                    continue; // 次のレコードへ
                }

                $corporation = RailwayCompany::firstOrCreate(['es_code' => $c->code], $r);
                if (!$corporation->wasRecentlyCreated) { // update(既存レコードあり)
                    $corporation->name = $c->Name;
                    $corporation->status = 1;
                    $corporation->update();
                }
            } // for loop end

            // 取り損ねの場合処理を抜ける
            if (intval($result->max ?? 0) < self::LIMIT) return intval($result->max ?? 0);

            $offset += self::LIMIT;
            ++$count;
        } // for loop end

        return $count;
    }

    /**
     * 都道府県別路線情報更新
     *
     * @return integer $count 登録/更新件数
     */
    private function updatePrefectureRail()
    {
        // 対象取得
        $prefecture_rails = PrefectureRail::select('prefecture_id')
            ->where('status', '1')
            ->groupBy('prefecture_id')
            ->orderBy('prefecture_id')->get();
        $count = 0;
        foreach ($prefecture_rails as $p) {

            $offset = 1;
            $param = '&prefectureCode=' . $p->prefecture_id . '&offset=';
            $all_count = $this->getAllCount(self::OPERATION_LINE_URL, $param . $offset);
            $page = intval(ceil($all_count / self::LIMIT));
            for ($i = 0; $i < $page; $i++) {

                // API request
                $url = self::OPERATION_LINE_URL . urlencode(self::EKISPERT_API_KEY) . $param . $offset;
                $result = $this->getJsonElements($url);

                // 路線情報取得
                $line = $result->Line ?? [];
                if (empty($line))
                    throw new \Exception('応答データに Line が存在しませんでした。' . PHP_EOL . print_r($result, true));

                foreach ($line as $l) {
                    $v = new PrefectureRail();
                    $r = [
                        'prefecture_id' => $p->prefecture_id,
                        'rail_id' => $l->code,
                        'status' => 1,
                    ];

                    if (!$v->validate($r)) {
                        Log::error($v->errors());
                        continue; // 次のレコードへ
                    }

                    $prefecture_rail = PrefectureRail::firstOrCreate([
                        'prefecture_id' => $p->prefecture_id,
                        'rail_id' => $l->code,
                    ], $r);
                    if (!$prefecture_rail->wasRecentlyCreated) { // update(既存レコードあり)
                        $prefecture_rail->rail_id = $l->code;
                        $prefecture_rail->status = 1;
                        $prefecture_rail->update();
                    }
                    ++$count;
                } // for loop end

                // 取り損ねの場合処理を抜ける
                if (intval($result->max ?? 0) < self::LIMIT) return intval($result->max ?? 0);

                $offset += self::LIMIT;
            }
        }

        return $count;
    }

    /**
     * 運行路線別駅情報更新
     *
     * @return integer $count 登録/更新件数
     */
    private function updateRailStation()
    {

        // 対象取得
        $rail_stations = RailStation::where('status', '1')->orderBy('rail_id')->get();
        $count = 0;
        foreach ($rail_stations as $rail) {

            $offset = 1;
            $param = '&operationLineCode=' . $rail->rail_id . '&offset=';
            $all_count = $this->getAllCount(self::STATION_URL, $param . $offset);
            $page = intval(ceil($all_count / self::LIMIT));
            for ($i = 0; $i < $page; $i++) {

                // API request
                $url = self::STATION_URL . urlencode(self::EKISPERT_API_KEY) . $param . $offset;
                $result = $this->getJsonElements($url);

                // 路線情報取得
                $points = $result->Point ?? [];
                if (empty($points))
                    throw new \Exception('応答データに Point が存在しませんでした。' . PHP_EOL . print_r($result, true));

                foreach ($points as $p) {

                    $station = $p->Station ?? [];
                    if (empty($station))
                        throw new \Exception('応答データに Station が存在しませんでした。' . PHP_EOL . print_r($result, true));

                    $v = new RailStation();
                    $r = [
                        'rail_id' => $rail->rail_id,
                        'station_id' => $station->code,
                        'status' => 1,
                    ];

                    if (!$v->validate($r)) {
                        Log::error($v->errors());
                        continue; // 次のレコードへ
                    }

                    $rail_station = RailStation::firstOrCreate(['rail_id' => $rail->rail_id], $r);
                    if (!$rail_station->wasRecentlyCreated) { // update(既存レコードあり)
                        $rail_station->station_id = $station->code;
                        $rail_station->status = 1;
                        $rail_station->update();
                    }

                    ++$count;
                } // for loop end

                // 取り損ねの場合処理を抜ける
                if (intval($result->max ?? 0) < self::LIMIT) return intval($result->max ?? 0);

                $offset += self::LIMIT;
            }
        }

        return $count;
    }

    /**
     * 対象取得
     *
     * @param  string $url 駅スパートAPI URL
     * @return \Illuminate\Http\Response
     */
    private function getJsonElements($url)
    {
        $client = new Client();
        $response = $client->request('GET', $url);
        $result = $this->getResultSet($response);
        return $result;
    }

    /**
     * ResultSet要素取得
     *
     * @param  Psr7\Http\Message\ResponseInterface $response
     * @return ResultSet
     */
    private function getResultSet($response)
    {
        $body = json_decode($response->getBody());
        if (!property_exists($body, 'ResultSet')) {
            throw new \Exception('応答データに ResultSet が存在しませんでした。' . PHP_EOL . print_r($body, true));
        }
        if (property_exists($body->ResultSet, 'Error')) {
            throw new \Exception('応答エラー' . PHP_EOL . print_r($body->ResultSet, true));
        }
        return $body->ResultSet;
    }
}

<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Filters\Customer\CustomerFilters;
use App\Http\Requests\CustomerFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller {


	public function index( CustomerFilters $customerFilters ) {

		$pagination = ( in_array( request()->get( 'pagination' ), [
			10,
			20,
			50,
			100,
		] ) ) ? request()->get( 'pagination' ) : 10;

		$customers       = Customer::filter( $customerFilters )->orderBy( 'id', 'asc' )->paginate( $pagination );
		$customer_detail = [];// Customer::findOrFail( 1 );
		$reservations    = [];// $customer_detail->reservations()->paginate( 2 );

		return view( 'customer.index', [ 'customers'       => $customers] );

	}


	public function detail( Request $request ) {

		$page_number     = ( $request->page_id ) ?? 1;
		$customer_detail = Customer::findOrFail( $request->id );
		$reservations    = $customer_detail->reservations()->paginate( 10, [ '*' ], 'page', $page_number );

		return response()->json( [
			'data' => view( 'customer.partials.detail.tab-content', [
				'customer_detail' => $customer_detail,
				'reservations'    => $reservations,
			] )->render(),
		] );
	}

	/**
	 * CSV file import for customer
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function importData( Request $request ) {

		$csv_file_path = $request->file( 'file_selection' )->storeAs( 'customer-csv', time() . '.csv' );
		$customerArr   = csvToArray( storage_path( 'app/' . $csv_file_path ) );
		$savedCustomer = DB::table( 'customers' )->insert( $customerArr );

		if ( $savedCustomer ) {
			return redirect( 'customer' )->with( 'success', trans( 'messages.created', [ 'name' => trans( 'messages.names.customers' ) ] ) );
		} else {
			return redirect( 'customer' )->with( 'error', trans( 'messages.invalid_format', [ 'name' => trans( 'messages.names.customers' ) ] ) );
		}
	}


	public function create() {
		return view( 'customer.create' );
	}


	public function store( CustomerFormRequest $request ) {

		if ( Customer::create( $request->all() ) ) {
			return redirect( 'customer' )->with( 'success', trans( 'messages.created', [ 'name' => trans( 'messages.names.customers' ) ] ) );
		} else {
			return redirect( 'customer' )->with( 'error', trans( 'messages.invalid_format', [ 'name' => trans( 'messages.names.customers' ) ] ) );
		}
	}


	public function edit( Customer $customer ) {

		$customer_detail = Customer::findOrFail( $customer->id );

		return view( 'customer.edit', [ 'customer_detail' => $customer_detail ] );
	}


	public function update( Request $request, Customer $customer ) {

		$customer = Customer::findOrFail( $customer->id );

		if ( $customer->update( $request->all() ) ) {
			return redirect( 'customer' )->with( 'success', trans( 'messages.updated', [ 'name' => trans( 'messages.names.customers' ) ] ) );
		} else {
			return redirect( 'customer' )->with( 'error', trans( 'messages.invalid_format', [ 'name' => trans( 'messages.names.customers' ) ] ) );
		}

	}

	public function destroy( Customer $customer ) {

		$customer = Customer::findOrFail( $customer->id );
		$customer->delete();

		return redirect( 'customer' )->with( 'success', trans( 'messages.deleted', [ 'name' => trans( 'messages.names.customers' ) ] ) );

	}


	public function showEmailForm( $customer_id ) {

		$customer = Customer::findOrFail( $customer_id );

		return response()->json( [
			'data' => view( 'customer.partials.email', [
				'customer' => $customer,
			] )->render(),
		] );
	}


	public function emailSend( $customer_id ) {
		dd( $customer_id );
	}
}

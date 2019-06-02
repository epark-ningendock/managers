<?php

namespace Tests\Feature;

use App\EmailTemplate;
use App\Hospital;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EmailTemplateControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function testIndex()
    {
        $response = $this->call('GET', '/email-template');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate()
    {
        $response = $this->call('GET', '/email-template/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEdit()
    {
        $hospital = factory(Hospital::class)->create();
        $email_template = factory(EmailTemplate::class)->create([
            'hospital_id' => $hospital->id,
        ]);
        $response = $this->call('GET', "/email-template/$email_template->id/edit");
        $this->assertEquals(200, $response->getStatusCode());
    }
}

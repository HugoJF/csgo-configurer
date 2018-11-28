<?php

namespace Tests\Unit;

use App\Classes\Breadcrumb;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BreadCrumbTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBreadcrumbHelperCanGenerateArrayCorrectly()
    {
    	$bc = new Breadcrumb('Base', 'Base');
    	$bc->addCurrent('Current');
    	$bc->add('Text');
    	$bc->addRoute('Route', 'my-route');
    	$bc->addUrl('Url', 'www.example.com');

    	$output = $bc->render();

        $this->assertEquals($output, [
        	[
        		'text' => 'Base',
				'route' => 'Base',
			], [
				'text' => 'Current',
				'url' => 'http://localhost',
			], [
				'text' => 'Text',
				'route' => null,
			], [
				'text' => 'Route',
				'route' => 'my-route',
			], [
				'text' => 'Url',
				'url' => 'www.example.com',
			]
		]);
    }
}

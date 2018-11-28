<?php

namespace Tests\Unit;

use App\Classes\SmartLog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmartLogTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSmartLogCanGenerateTextOutput()
    {
    	$sl = new SmartLog();

    	$sl->addMeasure('My first measure');
    	usleep(20000);
    	$sl->addMeasure('After 20ms');
    	$sl->addMessage('Random message');
    	$output = $sl->render();

    	$result19 = "[generic]: My first measure (took 19 ms to finish)\n[generic]: After 20ms\n[generic]: Random message\n";
    	$result20 = "[generic]: My first measure (took 20 ms to finish)\n[generic]: After 20ms\n[generic]: Random message\n";
    	$result21 = "[generic]: My first measure (took 21 ms to finish)\n[generic]: After 20ms\n[generic]: Random message\n";

    	$right = $result19 == $output || $result20 == $output || $result21 == $output;

        $this->assertContains($output, [$result19, $result20, $result21]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // USER TESTS
	/**
	 * A user can create a list
	 */

	// CONFIG TESTS
	/**
	 * A config can be edited
	 * A config can be deleted
	 *
	 * A config has a base list
	 *
	 * A config with higher priority overwrites a lower priority one
	 * A config can be merged with another one
	 */

	// LISTS TESTS
	/**
	 * A list can be created
	 * A list can be edited
	 * A list can be deleted
	 *
	 * A list can be created from a field-list
	 *
	 * A list can have sub-lists
	 */

	// CONSTANTS TESTS
	/**
	 * A constant can be created
	 * A constant can be edited
	 * A constant can be deleted
	 */

	// SYNCHRONIZATION TESTS
	/**
	 * A server sync creates a sync report
	 */

	// RENDER TESTS
	/**
	 * A server render creates a render report
	 */

	// SERVER TESTS
	/**
	 * A server can be created
	 * A server can be edited
	 * A server can be deleted
	 *
	 * A server can be rendered
	 * A server can be synced
	 *
	 * A server can create a config
	 *
	 * A server can correctly preview an installation file
	 */

	// FILE TESTS
	/**
	 * A plugin file scan attaches files correctly
	 *
	 * A server sync attaches files correctly (synced and backup)
	 * A server render attaches files correctly
	 */

	// PLUGIN TESTS
	/**
	 * A plugin can create a config
	 */

	// INSTALLATION TESTS
	/**
	 * An installation can be created
	 * An installation can be edited
	 * An installation can be deleted
	 *
	 * An installation can add new plugins to its collection
	 * An installation can remove plugins from its collection
	 *
	 * An installation plugin can select a config
	 */

	// FIELDLISTS TESTS
	/**
	 * A field list can be created
	 * A field list can be edited
	 * A field list can be deleted
	 */

	// FIELD TESTS
	/**
	 * A field can be created
	 * A field can be edited
	 * A field can be deleted
	 */

	// COMPOUND VARIABLE TRANSLATOR TESTS
	/**
	 * A CVT can correctly replace compound variables
	 */

	// SMART LOG TESTS
	/**
	 * SmartLogs can generate a text log
	 */

}

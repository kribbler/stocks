<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TradesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TradesTable Test Case
 */
class TradesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TradesTable
     */
    public $Trades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.trades',
        'app.stocks'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Trades') ? [] : ['className' => 'App\Model\Table\TradesTable'];
        $this->Trades = TableRegistry::get('Trades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Trades);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

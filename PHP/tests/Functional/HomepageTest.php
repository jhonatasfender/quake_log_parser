<?php

namespace Tests\Functional;

class HomepageTest extends BaseTestCase {

    public function testGetHome() {
        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Inicio', (string)$response->getBody());
    }

    public function testGetJson() {
        $response = $this->runApp('GET', '/json');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('retorno do object construido', (string)$response->getBody());
    }

    public function testPostSearch() {
        $response = $this->runApp('POST', '/search',['search' => 'is']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('pesquisa', (string)$response->getBody());
    }
}
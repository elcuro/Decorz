<?php
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('DecorzComponent', 'Decorz.Controller/Component');
App::uses('Component', 'Controller');
App::uses('NodesController', 'Controller');
App::uses('CroogoTestCase', 'TestSuite');


class TestDecorzComponent extends DecorzComponent {

    public $files = array();

    public function dispatchAction($action) {
        $this->_dispatchAction($action);
    }

    protected function _path_exists($path) {
        if (in_array($path, $this->files)) {
            return true;
        }
        return false;
    }
}


class TestNodesController extends NodesController {
}


class DecorzComponentTest extends CroogoTestCase {

    public $fixtures = array(
        'aco',
        'aro',
        'aros_aco',
        'block',
        'comment',
        'contact',
        'i18n',
        'language',
        'link',
        'menu',
        'message',
        'meta',
        'node',
        'nodes_taxonomy',
        'region',
        'role',
        'setting',
        'taxonomy',
        'term',
        'type',
        'types_vocabulary',
        'user',
        'vocabulary',
    );


    public function setUp() {
        parent::setUp();
        // Setup our component and fake test controller
        CakePlugin::load('Decorz', array('bootstrap' => true));
        $Collection = new ComponentCollection();
        $this->DecorzComponent = new TestDecorzComponent($Collection);
        $CakeRequest = new CakeRequest();
        $CakeResponse = new CakeResponse();
        $this->Controller = new TestNodesController($CakeRequest, $CakeResponse);
    }       


    public function testIndexDecorImage() {

        $path = WWW_ROOT.'uploads'.DS.'decorz_index_node.jpg';
        $this->DecorzComponent->files = array($path);   
        $expected = $path;

        $this->Controller->request->params['type'] = 'node';
        $this->Controller->request->params['action'] = 'index';
        $this->DecorzComponent->beforeRender($this->Controller);
        $this->assertEqual($this->Controller->viewVars['Decorz']['server_path'], $expected);     
    }


    public function testTermDecorImage() {

        $path = WWW_ROOT.'uploads'.DS.'decorz_term.jpg';
        $this->DecorzComponent->files = array($path);   
        $expected = $path;

        $this->Controller->request->params['type'] = 'node';
        $this->Controller->request->params['action'] = 'term';
        $this->Controller->request->params['slug'] = 'term_slug';
        $this->DecorzComponent->beforeRender($this->Controller);
        $this->assertEqual($this->Controller->viewVars['Decorz']['server_path'], $expected);     
    }    


    public function testDefaultDecorImage() {

        $path = WWW_ROOT.'uploads'.DS.'decorz_default.jpg';
        $this->DecorzComponent->files = array($path);   
        $expected = $path;

        $this->Controller->request->params['type'] = 'node';
        $this->Controller->request->params['action'] = 'index';
        $this->DecorzComponent->beforeRender($this->Controller);
        $this->assertEqual($this->Controller->viewVars['Decorz']['server_path'], $expected);     
    }    


    public function tearDown() {
        parent::tearDown();
        unset($this->DecorzComponent);
        unset($this->Controller);
        ob_flush();
    }     
}
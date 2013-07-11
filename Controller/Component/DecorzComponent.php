<?php
App::uses('Component', 'Controller');
/**
 * Decorz component
 *
 * @author Juraj Jancuska <jjancuska@gmail.com>
 * @copyright (c) 2010 Juraj Jancuska
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class DecorzComponent extends Component {   

/**
 * Current controller
 *
 * @var object
 **/
    public $controller;

/**
 * Decorz directory
 *
 * @var string
 **/
    public $path;

/**
 * Default image extension
 *
 * @var string
 **/
    public $ext;

/**
* beforeRender
*
* @param object $controller
* @return array
*/
    public function beforeRender(Controller $controller) {

        //parent::beforeRender($controller);

        $this->path = WWW_ROOT.'uploads'.DS;
        $this->ext = Configure::read('Decorz.ext');
        $isAdmin = !empty($controller->request->params['admin']);
        if (!$isAdmin) {
            $this->controller = $controller;
            $this->_dispatchAction($controller->action);
        }
    }

/**
 * Dispatch method by action
 *
 * @param string $action
 * @return void
 **/
    protected function _dispatchAction($action) {

        $paths = false;
        $methodName = '_' . $action . 'Paths';

        if (method_exists($this, $methodName)) {
            $paths = $this->$methodName();
            if ($filename = $this->_fallback($paths)) {
                $this->controller->set('Decorz', array(
                    'server_path' => $this->path.$filename,
                    'relative_path' => '/'.$filename)
                );
            }
        }
    }

/**
 * Decor paths for index action
 *
 * @return void
 **/
    protected function _indexPaths() {

        $type = $this->controller->request->params['type'];
        $files = array(
            'index_'.$type.'.'.$this->ext,
            'index.'.$this->ext
        );
        
        return $files;
    }

/**
 * Decor paths for term action
 *
 * @return void
 **/
    protected function _termPaths() {

        $term = $this->controller->request->params['slug'];
        $type = $this->controller->type;
        $files = array(
            'term_'.$term.'.'.$this->ext,
            'term_'.$type.'.'.$this->ext,
            'term.'.$this->ext
        );
        
        return $files;
    }

/**
 * Decor paths for view action
 *
 * @return void
 **/
    protected function _viewPaths() {

        $slug = $this->controller->request->params['slug'];
        $type = $this->controller->type;
        $files = array(
            'view_'.$slug.'.'.$this->ext,
            'view_'.$type.'.'.$this->ext,
            'view.'.$this->ext);
        
        return $files;
    }

/**
 * Fallback check for decor image
 *
 * @param array $files Files for check
 * @return string Filename
 **/
    protected function _fallback($files) {

        $files[] = 'default.'.$this->ext;
        $prefix = Configure::read('Decorz.prefix');
        foreach($files as $filename) {
            $path = $this->path.$prefix.$filename;
            if ($this->_path_exists($path)) {
                return basename($path);
            }
        }

        return false;
    }

/**
 * Check if file exist
 *
 * @return boolean
 **/
    protected function _path_exists($path) {

        if (file_exists($path)) {
            return true;
        }
        
        return false;
    }
}

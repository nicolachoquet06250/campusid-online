<?php
namespace View;

class IndexView extends \View\View
{
    public function init()
    {

        if (isset($this->router)) {
            $this->assign('router', $this->router);
        }

        /* Domyślne alerty */
        if ($this->baseClass->get_msg()->hasMessages('error')) {
            $this->assign('msgError', $this->baseClass->get_msg()->display('error'));
        }
        
        if ($this->baseClass->get_msg()->hasMessages('success')) {
            $this->assign('msgSuccess', $this->baseClass->get_msg()->display('success'));
        }
        
        if ($this->baseClass->get_msg()->hasMessages('warning')) {
            $this->assign('msgWarning', $this->baseClass->get_msg()->display('warning'));
        }
        
        if ($this->baseClass->get_msg()->hasMessages('info')) {
            $this->assign('msgInfo', $this->baseClass->get_msg()->display('info'));
        }

    }

}

<?php
namespace View;

use Dframe\Messages;

class IndexView extends \View\View
{
    public function init()
    {

        if (isset($this->router)) {
            $this->assign('router', $this->router);
        }

        /** @var Messages $msg */
        $msg = $this->baseClass->get_service_message();


        /* Domyślne alerty */
        if ($msg->hasMessages('error')) {
            $this->assign('msgError', $msg->display('error'));
        }
        
        if ($msg->hasMessages('success')) {
            $this->assign('msgSuccess', $msg->display('success'));
        }
        
        if ($msg->hasMessages('warning')) {
            $this->assign('msgWarning', $msg->display('warning'));
        }
        
        if ($this->baseClass->get_msg()->hasMessages('info')) {
            $this->assign('msgInfo', $this->baseClass->get_msg()->display('info'));
        }

    }

}

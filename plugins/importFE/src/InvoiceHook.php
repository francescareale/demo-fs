<?php

namespace Plugins\ImportFE;

use Hooks\CachedManager;
use Modules;

class InvoiceHook extends CachedManager
{
    public function getCacheName()
    {
        return 'Fatture Elettroniche';
    }

    public function cacheData()
    {
        return Interaction::getInvoiceList();
    }

    public function response()
    {
        $results = $this->getCache()->content;

        $count = count($results);
        $notify = false;

        $module = Modules::get('Fatture di acquisto');
        $plugins = $module->plugins;

        if (!empty($plugins)) {
            $notify = !empty($count);

            $plugin = $plugins->first(function ($value, $key) {
                return $value->name == 'Fatturazione Elettronica';
            });

            $link = ROOTDIR.'/controller.php?id_module='.$module->id.'#tab_'.$plugin->id;
        }

        $message = tr('Ci sono _NUM_ fatture passive da importare', [
            '_NUM_' => $count,
        ]);

        return [
            'icon' => 'fa fa-file-text-o text-yellow',
            'link' => $link,
            'message' => $message,
            'show' => $notify,
        ];
    }
}

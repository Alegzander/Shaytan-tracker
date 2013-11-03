<?php
/**
 * User: alegz
 * Date: 10/29/13
 * Time: 8:31 AM
 */

class NetworkController extends ShaytanController {
    public function actionCreate(){
        $zone = new Zone();
        $post = \Yii::app()->request->getPost(get_class($zone));

        $zone->setAttributes($post);

        if (isset($post) && $zone->validate()){
            $zone->save();

            foreach ($zone->subnetList as $subnetString){
                $network = OSHelper::network()->getNetworkFromSubnet($subnetString, true);
                $mask = OSHelper::network()->getMaskFromSubnet($subnetString);

                $networkModel = Network::model()->findOne(array(
                    'network' => $network,
                    'mask' => $mask
                ));

                if (!isset($networkModel)){
                    $networkModel = new Network();
                    $networkModel->zoneId = $zone->getAttribute($zone->primaryKey());
                } else {
                    $zone->addError('subnetList', \Yii::t('error', 'Subnet {subnet} already exist in database and belongs to "{zone}" zone.', array(
                        '{subnet}' => $subnetString,
                        '{zone}' => Zone::model()->findByPk($networkModel->zoneId)->name,
                    )));
                    $zone->delete();
                    unset($networkModel);
                    continue;
                }

                $networkModel->network = $network;
                $networkModel->mask = $mask;
                $networkModel->minIp = OSHelper::network()->getMinimumIpInSubnet($subnetString, true);
                $networkModel->maxIp = OSHelper::network()->getMaximumIpInSubnet($subnetString, true);
                $networkModel->save();

                unset($networkModel);
            }

            if (!$zone->hasErrors())
                $this->redirect($this->createUrl('/shaytan/network/view', array('id' => $zone->name)));
        }

        if (is_array($zone->subnetList))
            $zone->subnetList = implode(', ', $zone->subnetList);

        $this->render('create', array('model' => $zone));
    }

    public function actionView(){
        $zones = Zone::model()->search();

        $this->render('view', array('zones' => $zones));
    }
} 
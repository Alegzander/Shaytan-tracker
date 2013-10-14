<?php

class m131014_182925_adding_basic_permissions extends PermissionMigration
{
    protected $operations = array(
        'SiteIndex',
        'SiteError',
        'SiteCaptcha',
        'SitePage',
        'TorrentCreate'
    );
    protected $tasks = array(
        'SiteViewing',
        'TorrentViewing',
        'TorrentAdministrating'
    );
    protected $roles = array(
        'Administrator',
        'Operator',
        'User'
    );

    protected $permissionHierarchy = array(
        'SiteViewing' => array(
            'SiteIndex',
            'SiteError',
            'SiteCaptcha',
            'SitePage'
        ),
        'TorrentViewing' => array(
            'TorrentCreate'
        ),
        'TorrentAdministrating' => array(
            'TorrentCreate'
        ),
        'Administrator' => array(
            'SiteViewing',
            'TorrentViewing',
            'TorrentAdministrating'
        ),
        'Operator' => array(
            'SiteViewing',
            'TorrentViewing',
            'TorrentAdministrating'
        ),
        'User' => array(
            'SiteViewing',
            'TorrentViewing'
        )
    );
}
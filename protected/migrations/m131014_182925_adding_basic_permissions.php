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
        'TorrentCreating',
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
        'TorrentCreating' => array(
            'TorrentCreate'
        ),
        'Administrator' => array(
            'SiteViewing',
            'TorrentCreating',
            'TorrentViewing',
            'TorrentAdministrating'
        ),
        'Operator' => array(
            'SiteViewing',
            'TorrentCreating',
            'TorrentViewing',
            'TorrentAdministrating'
        ),
        'User' => array(
            'SiteViewing',
            'TorrentCreating'
        )
    );
}
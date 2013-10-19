<?php
/**
 * User: alegz
 * Date: 10/16/13
 * Time: 1:09 AM
 */

class ETorrentStatus extends BaseEnum {
    const _NEW_ = 0;
    const ON_MODERATION = 10;
    const APPROVED = 20;
    const RETURNED_TO_MODERATION = 30;
    const BLOCKED = 50;
    const PROHIBITED = 60;

}
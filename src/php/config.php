<?php

namespace Config;

const host = 'mysql';
const user = 'root';
const password = 'root';
const dbname = 'content';

const src_dir = '../..';
const public_dir = src_dir . '/public';
const sql_dir = src_dir . '/sql';
const avatars_dir = public_dir . '/avatars';
const avatars_url = '/avatars';

const max_posts_per_page = 10;

const logout_after = 3600;

const min_username_length = 3;
const max_username_length = 20;
const min_password_length = 6;
const max_password_length = 20;

?>

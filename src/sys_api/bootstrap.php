<?php
date_default_timezone_set('Asia/Shanghai');
XAop::append_by_match_uri(".*", new AccessAllow());

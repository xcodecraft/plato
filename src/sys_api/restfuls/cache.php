<?php


//@REST_RULE: /v2/cache/$method
class CacheREST extends XRuleService implements XService
{

    public function data($xcontext, $request, $response)
    {
        $scene = $_GET['scene'];
        $data  = \plato\service\Cache::get($scene);
        $response->success($data);
    }

}

<?php

class MenuController extends XRuleService implements XService //@REST_RULE: /v2/user/menu/$method
{
    /**
     * @api      {get} /v1/user/menu/get  获得菜单
     * @apiName  user_menu_get
     * @apiGroup User
     * @apiUse   APICommon
     */
    public function get($xcontext, $request, $response)
    {
        $arr   = [];
        $m1    = new Menu(1000, "Plato", "");
        $menus = [
            "/project/lists" => "项目列表",
        ];
        foreach ($menus as $uri => $title) {
            $m1->addChild($title, $uri);
        }
        $m1->toArr($arr);
        $response->success($arr);
    }
}


class Menu
{
    public function __construct($id, $title, $url)
    {
        $this->id      = $id;
        $this->childID = $id;
        $this->title   = $title;
        $this->url     = $url;
        $this->childs  = [];
    }

    public function addChildNode($menu)
    {
        $this->childs[$menu->id] = $menu;
    }

    public function addChild($title, $url)
    {
        $id                = ++$this->childID;
        $this->childs[$id] = new Menu($id, $title, $url);
    }

    public function toArr(&$arr)
    {
        $arrs = [];
        foreach ($this->childs as $child) {
            $child->toArr($arrs);
        }
        $arr[] = [
            'id'       => $this->id,
            'title'    => $this->title,
            'url'      => $this->url,
            'children' => $arrs
        ];
    }
}

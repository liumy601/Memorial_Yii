<?php
/**
 * Common render php, html
 */


class CommonRender {
  
  public static function shotCuts()
  {
    if (is_array(Yii::app()->params['subMenu']) && count(Yii::app()->params['subMenu'])) {
      foreach (Yii::app()->params['subMenu'] as $i=>$menu) {
        $createNewBtn = $importNewBtn = $buttonurl = '';
        
        if (substr($menu['text'], 0, 4) == 'New ') {
          $createNewBtn = ' createNewBtn';
        }
        if (substr($menu['text'], 0, 7) == 'Import ') {
          $createNewBtn = ' importNewBtn';
        }
        
        $id = str_replace(' ', '_', $menu['text']);
        
        if($menu['noajax']){//noajax, such as homepage
          echo '<input id="'.$id.'" type="button" class="btn" value="'. $menu['text'] .'" pagetitle="'. $menu['text'] .'" onclick="document.location.href=\''. $menu['url'] .'\'"> ';
        } else {
          echo '<input id="'.$id.'" type="button" class="btn buttonurl' . $createNewBtn . $createNewBtn . $noajax .'" value="'. $menu['text'] .'" pagetitle="'. $menu['text'] .'" url="'. $menu['url'] .'"> ';
        }
      }
    }
  }
  
}
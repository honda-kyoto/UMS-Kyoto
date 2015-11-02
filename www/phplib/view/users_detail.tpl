<?php include("view/head.tpl") ?>
      <input type="hidden" name="user_id" id="user_id" value="<?php echo $mgr->getRequestData('user_id') ?>">
      <input type="hidden" name="ctrl_mode_name" id="ctrl_mode_name" value="<?php echo $mgr->getRequestData('ctrl_mode_name') ?>">
      <input type="hidden" name="disp_type_name" id="disp_type_name" value="<?php echo $mgr->getRequestData('disp_type_name') ?>">
      <input type="hidden" name="col_name" id="col_name" value="">
      <div id="topBtnArea">
        <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
      </div>
      <div style="clear:both;"></div>
      <div id="tabs1">
        <ul>
<?php echo $mgr->getUsersTabMenu() ?>
        </ul>
      </div>
      <div style="clear:both;"></div>
      <div id="usersBox">
<?php include($mgr->getViewFileName("/users/".$mgr->getRequestData('ctrl_mode_name')."_detail_".$mgr->getRequestData('disp_type_name').".tpl")) ?>
      </div>
<?php include("view/foot.tpl") ?>

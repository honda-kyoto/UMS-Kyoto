<?php include("view/head.tpl") ?>
      <input type="hidden" name="app_id" id="app_id" value="">
      <input type="hidden" name="entry_no" id="entry_no" value="">
      <input type="hidden" name="page" id="page" value="<?php echo $mgr->getRequestData('page') ?>">
      <input type="hidden" name="order" id="order" value="">
      <div id="topBtnArea">
      </div>
      <div style="clear:both;"></div>
<?php include("view/apps/search_cond_tab.tpl") ?>
<?php if (is_array(@$mgr->aryList)) { ?>
      <div class="resultBlock">
        <h3>■承認待ち一覧</h3>
        <div style="font-size: 12px;color: #f00;padding-bottom: 5px;">※すでに他の管理者によって承認・却下されている場合または申請者によって申請取消されている場合、一覧に表示されません。</div>
<?php   if (count($mgr->aryList) > 0) { ?>
        <table cellspacing="0" class="listTabH">
          <tr>
            <td><?php echo $mgr->getListNavi() ?></td>
            <td align="right">表示件数：
                <select name="list_max" onChange="listMax();">
                <?php echo $mgr->getSelectList('list_max') ?>
              </select></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab">
          <tr>
            <th scope="col" width="56"><a class="sortlink" href="javascript:sort('app_id');"><img src="<?php echo $mgr->getOrderData('src', 'app_id') ?>" alt="<?php echo $mgr->getOrderData('msg', 'app_id') ?>">ID</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('app_type_name');"><img src="<?php echo $mgr->getOrderData('src', 'app_type_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'app_type_name') ?>">機器種別</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('vlan_area_name');"><img src="<?php echo $mgr->getOrderData('src', 'vlan_area_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'vlan_area_name') ?>">設置場所</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('mac_addr');"><img src="<?php echo $mgr->getOrderData('src', 'mac_addr') ?>" alt="<?php echo $mgr->getOrderData('msg', 'mac_addr') ?>">MACアドレス</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('ip_addr');"><img src="<?php echo $mgr->getOrderData('src', 'ip_addr') ?>" alt="<?php echo $mgr->getOrderData('msg', 'ip_addr') ?>">IPアドレス</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('app_name');"><img src="<?php echo $mgr->getOrderData('src', 'app_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'app_name') ?>">名称</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('entry_user_name');"><img src="<?php echo $mgr->getOrderData('src', 'entry_user_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'entry_user_name') ?>">申請者</a></th>
            <th width="60" scope="col">詳細</th>
            <th scope="col"><a class="sortlink" href="javascript:sort('entry_status');"><img src="<?php echo $mgr->getOrderData('src', 'entry_status') ?>" alt="<?php echo $mgr->getOrderData('msg', 'entry_status') ?>">状況</a></th>
          </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
          <tr>
            <td align="right"><?php echo $id ?></td>
            <td align="center"><?php echo $data['app_type_name'] ?></td>
            <td><?php echo $data['vlan_area_name'] ?></td>
            <td align="center"><?php echo $data['mac_addr'] ?></td>
            <td align="center"><?php echo $data['ip_addr'] ?></td>
            <td><?php echo $data['app_name'] ?></td>
            <td align="center" nowrap><?php echo $data['entry_user_name'] ?></td>
            <td align="center">[<a href="javascript:appsAgree(<?php echo $id ?>, <?php echo $data['entry_no'] ?>);">詳細</a>]</td>
            <td align="center"><?php echo $data['entry_kbn_status'] ?></td>
          </tr>
<?php     } ?>
        </table>
        <table cellspacing="0" class="listTabB">
          <tr>
            <td><?php echo $mgr->getListNavi() ?></td>
          </tr>
        </table>
<?php   } else { ?>
        <table cellspacing="0">
          <tr>
            <td align="left">条件に一致するデータはありませんでした。</td>
          </tr>
        </table>
<?php   } ?>
      </div>
<?php } ?>
<?php include("view/foot.tpl") ?>


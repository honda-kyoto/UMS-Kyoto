<?php include("view/head.tpl") ?>
      <input type="hidden" name="vpn_id" id="vpn_id" value="">
      <input type="hidden" name="page" id="page" value="<?php echo $mgr->getRequestData('page') ?>">
      <input type="hidden" name="order" id="order" value="">
      <div id="topBtnArea">
<?php if ($mgr->isAdminUser()) { ?>
        <input type="button" onclick="formSubmit('input', 'vpns_add.php');" value="　　　新規登録　　　">
<?php } ?>
      </div>
      <div style="clear:both;"></div>
      <h3>■検索条件</h3>
      <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
        <tr>
          <th width="80">種別</th>
          <td><select name="vpn_kbn" id="vpn_kbn">
              <option value=""></option>
              <?php echo $mgr->getSelectList('vpn_kbn') ?>
            </select></td>
          <th width="160">ネットワークポリシー名</th>
          <td><input name="vpn_name" type="text" id="vpn_name" size="24" value="<?php echo $mgr->getRequestData('vpn_name') ?>" /></td>
          <th width="160">グループ／プロジェクト名</th>
          <td><input name="group_name" type="text" id="group_name" size="24" value="<?php echo $mgr->getRequestData('group_name') ?>"  /></td>
        </tr>
      </table>
      <div class="searchBlock">
        <a href="javascript:search();"  /><img src="image/btn_search.gif" title="検索" /></a>
        <a href="javascript:clearCond();" /><img src="image/btn_clear.gif" title="条件クリア" /></a>
      </div>

<?php if (is_array(@$mgr->aryList)) { ?>
      <div class="resultBlock">
        <h3>■検索結果一覧</h3>
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
            <th scope="col" width="56"><a class="sortlink" href="javascript:sort('vpn_id');"><img src="<?php echo $mgr->getOrderData('src', 'vpn_id') ?>" alt="<?php echo $mgr->getOrderData('msg', 'vpn_id') ?>">ID</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('vpn_kbn');"><img src="<?php echo $mgr->getOrderData('src', 'vpn_kbn') ?>" alt="<?php echo $mgr->getOrderData('msg', 'vpn_kbn') ?>">種別</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('vpn_name');"><img src="<?php echo $mgr->getOrderData('src', 'vpn_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'vpn_name') ?>">ネットワークポリシー名</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('group_name');"><img src="<?php echo $mgr->getOrderData('src', 'group_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'group_name') ?>">グループ／プロジェクト名</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('admin_name');"><img src="<?php echo $mgr->getOrderData('src', 'admin_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'admin_name') ?>">管理者</a></th>
            <th width="80" scope="col">参加者</th>
<?php     if ($mgr->isAdminUser()) { ?>
            <th width="60" scope="col">詳細</th>
            <th width="60" scope="col">削除</th>
<?php     } ?>
          </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
          <tr>
            <td align="right"><?php echo $id ?></td>
            <td><?php echo $data['kbn_name'] ?></td>
            <td><?php echo $data['vpn_name'] ?></td>
            <td><?php echo $data['group_name'] ?></td>
            <td><?php echo $data['admin_name'] ?></td>
            <td align="center">[<a href="javascript:memberList(<?php echo $id ?>);">リスト</a>]</td>
<?php       if ($mgr->isAdminUser()) { ?>
            <td align="center">[<a href="javascript:vpnsEdit(<?php echo $id ?>);">詳細</a>]</td>
            <td align="center">[<a href="javascript:vpnsDelete(<?php echo $id ?>);">削除</a>]</td>
<?php       } ?>
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


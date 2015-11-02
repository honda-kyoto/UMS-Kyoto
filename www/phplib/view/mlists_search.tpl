<?php include("view/head.tpl") ?>
      <input type="hidden" name="mlist_id" id="mlist_id" value="">
      <input type="hidden" name="entry_no" id="entry_no" value="">
      <input type="hidden" name="page" id="page" value="<?php echo $mgr->getRequestData('page') ?>">
      <input type="hidden" name="order" id="order" value="">
      <div id="topBtnArea">
<?php if ($mgr->isNormalUser()) { ?>
        <input type="button" onclick="formSubmit('input', 'mlists_entry.php');" value="　　　登録申請　　　">
<?php } else { ?>
        <input type="button" onclick="formSubmit('input', 'mlists_add.php');" value="　　　新規登録　　　">
<?php } ?>
      </div>
      <div style="clear:both;"></div>
<?php include("view/mlists/search_cond_tab.tpl") ?>
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
            <th scope="col" width="56"><a class="sortlink" href="javascript:sort('mlist_id');"><img src="<?php echo $mgr->getOrderData('src', 'mlist_id') ?>" alt="<?php echo $mgr->getOrderData('msg', 'mlist_id') ?>">ID</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('mlist_name');"><img src="<?php echo $mgr->getOrderData('src', 'mlist_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'mlist_name') ?>">名称</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('mlist_acc');"><img src="<?php echo $mgr->getOrderData('src', 'mlist_acc') ?>" alt="<?php echo $mgr->getOrderData('msg', 'mlist_acc') ?>">アカウント</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('mlist_kbn');"><img src="<?php echo $mgr->getOrderData('src', 'mlist_kbn') ?>" alt="<?php echo $mgr->getOrderData('msg', 'mlist_kbn') ?>">種別</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('admin_name');"><img src="<?php echo $mgr->getOrderData('src', 'admin_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'admin_name') ?>">管理者</a></th>
            <th width="80" scope="col">参加者</th>
            <th width="60" scope="col">詳細</th>
<?php if ($mgr->isNormalUser()) { ?>
            <th width="80" scope="col">削除申請</th>
<?php } else { ?>
            <th width="60" scope="col">削除</th>
<?php } ?>
            <th scope="col"><a class="sortlink" href="javascript:sort('entry_status');"><img src="<?php echo $mgr->getOrderData('src', 'entry_status') ?>" alt="<?php echo $mgr->getOrderData('msg', 'entry_status') ?>">申請</a></th>
          </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
          <tr>
            <td align="right"><?php echo $id ?></td>
            <td><?php echo $data['mlist_name'] ?></td>
            <td><?php echo $data['mlist_acc'] ?></td>
            <td><?php echo $data['kbn_name'] ?></td>
            <td><?php echo $data['admin_name'] ?></td>
<?php if ($mgr->isNormalUser()) { ?>
<?php   if ($data['is_pending_data']) { ?>
            <td align="center">-</td>
            <td align="center">[<a href="javascript:mlistsPending(<?php echo $id ?>, <?php echo $data['entry_no'] ?>);">詳細</a>]</td>
            <td align="center">-</td>
<?php   } else { ?>
            <td align="center">[<a href="javascript:memberList(<?php echo $id ?>);">リスト</a>]</td>
            <td align="center">[<a href="javascript:mlistsRevise(<?php echo $id ?>);">詳細</a>]</td>
            <td align="center">[<a href="javascript:mlistsDelEntry(<?php echo $id ?>);">削除申請</a>]</td>
<?php   } ?>
<?php } else { ?>
<?php   if ($data['is_pending_data']) { ?>
            <td align="center">-</td>
            <td align="center">[<a href="javascript:mlistsPending(<?php echo $id ?>, <?php echo $data['entry_no'] ?>);">詳細</a>]</td>
            <td align="center">-</td>
<?php   } else { ?>
            <td align="center">[<a href="javascript:memberList(<?php echo $id ?>);">リスト</a>]</td>
            <td align="center">[<a href="javascript:mlistsEdit(<?php echo $id ?>);">詳細</a>]</td>
            <td align="center">[<a href="javascript:mlistsDelete(<?php echo $id ?>);">削除</a>]</td>
<?php   } ?>
<?php } ?>
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


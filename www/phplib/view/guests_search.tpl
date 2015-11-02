<?php include("view/head.tpl") ?>
      <input type="hidden" name="guest_id" id="guest_id" value="">
      <input type="hidden" name="page" id="page" value="<?php echo $mgr->getRequestData('page') ?>">
      <input type="hidden" name="order" id="order" value="">
      <div id="topBtnArea">
        <input type="button" onclick="formSubmit('input', 'guests_add.php');" value="　　　新規登録　　　">
      </div>
      <div style="clear:both;"></div>
      <h3>■検索条件</h3>
      <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
        <tr>
          <th width="110">ゲスト氏名</th>
          <td><input name="guest_name" type="text" id="guest_name" size="24" value="<?php echo $mgr->getRequestData('guest_name') ?>" /></td>
          <th width="110">会社名</th>
          <td><input name="company_name" type="text" id="company_name" size="24" value="<?php echo $mgr->getRequestData('company_name') ?>" /></td>
          <th width="110">MACアドレス</th>
          <td><input name="mac_addr" type="text" id="mac_addr" size="24" value="<?php echo $mgr->getRequestData('mac_addr') ?>"  /></td>
        </tr>
        <tr>
          <th width="110">登録日</th>
          <td><input name="entry_date" type="text" id="entry_date" onchange="cal_etd.getFormValue(); cal_etd.hide();" onclick="cal_etd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('entry_date') ?>" /><br /><div id="caldiv_etd"></div></td>
          <th width="110">期限切れ</th>
          <td colspan="3"><div class="CheckBoxTab">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><input type="checkbox" name="all_data_flg" id="all_data_flg" value="1" <?php echo $mgr->getCheckData('all_data_flg', '1') ?> /></td>
                <td><label for="all_data_flg">期限切れデータも表示する</label></td>
              </tr>
            </table>
          </div></td>
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
            <th scope="col"><input type="checkbox" name="allcheck" value="1" onClick="clickAllCheckBox(this, 'checked_id[]')" /></th>
            <th scope="col" width="56"><a class="sortlink" href="javascript:sort('guest_id');"><img src="<?php echo $mgr->getOrderData('src', 'guest_id') ?>" alt="<?php echo $mgr->getOrderData('msg', 'guest_id') ?>">ID</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('guest_name');"><img src="<?php echo $mgr->getOrderData('src', 'guest_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'guest_name') ?>">ゲスト氏名</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('company_name');"><img src="<?php echo $mgr->getOrderData('src', 'company_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'company_name') ?>">会社名</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('belong_name');"><img src="<?php echo $mgr->getOrderData('src', 'belong_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'belong_name') ?>">所属</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('telno');"><img src="<?php echo $mgr->getOrderData('src', 'telno') ?>" alt="<?php echo $mgr->getOrderData('msg', 'telno') ?>">電話番号</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('mac_addr');"><img src="<?php echo $mgr->getOrderData('src', 'mac_addr') ?>" alt="<?php echo $mgr->getOrderData('msg', 'mac_addr') ?>">MACアドレス</a></th>
<?php     if ($mgr->isAdminUser()) { ?>
            <th scope="col"><a class="sortlink" href="javascript:sort('make_id');"><img src="<?php echo $mgr->getOrderData('src', 'make_id') ?>" alt="<?php echo $mgr->getOrderData('msg', 'make_id') ?>">登録者</a></th>
<?php     } ?>
            <th scope="col"><a class="sortlink" href="javascript:sort('entry_time');"><img src="<?php echo $mgr->getOrderData('src', 'entry_time') ?>" alt="<?php echo $mgr->getOrderData('msg', 'entry_time') ?>">登録日時</a></th>
            <th width="60" scope="col">詳細</th>
            <th width="60" scope="col">削除</th>
          </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
          <tr>
            <td><input type="checkbox" name="checked_id[]" value="<?php echo $id ?>" /></td>
            <td align="right"><?php echo $id ?></td>
            <td><?php echo $data['guest_name'] ?></td>
            <td><?php echo $data['company_name'] ?></td>
            <td><?php echo $data['belong_name'] ?></td>
            <td><?php echo $data['telno'] ?></td>
            <td><?php echo $data['mac_addr'] ?></td>
<?php       if ($mgr->isAdminUser()) { ?>
            <td><?php echo $data['entry_name'] ?></td>
<?php       } ?>
            <td><?php echo $data['entry_time'] ?></td>
            <td align="center">[<a href="javascript:guestsDetail(<?php echo $id ?>);">詳細</a>]</td>
            <td align="center">[<a href="javascript:guestsDelete(<?php echo $id ?>);">削除</a>]</td>
          </tr>
<?php     } ?>
        </table>
        <table cellspacing="0" class="listTabB">
          <tr>
            <td><?php echo $mgr->getListNavi() ?></td>
            <td align="right"><div class="inputComment">※発行するゲスト情報を選択してください。</div></td>
          </tr>
          <tr>
            <td colspan="2" align="right"><input type="button" name="btnPrint" value="　　　ゲストID通知書を発行　　　" OnClick="javascript:printGuests();"></td>
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


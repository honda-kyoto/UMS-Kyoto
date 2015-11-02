<?php include("view/head.tpl") ?>
      <input type="hidden" name="belong_class_id" id="belong_class_id" value="">
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　マスタメンテナンスに戻る　　　" onClick="location.href='msts.php'" /></div></td>
          </tr>
        </table>
        <h3>■所属分類一覧</h3>
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　全て更新　　　" onclick="mstEditAll();"></div></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab" id="listTab">
          <tr class="nodrop nodrag">
            <th scope="col" width="60">表示順</th>
            <th scope="col">所属分類名</th>
            <th scope="col" width="120">所属部門</th>
            <th scope="col" width="50">更新</th>
            <th scope="col" width="50">削除</th>
          </tr>
          <tr class="nodrop nodrag">
            <td align="center">（新規）</td>
            <td><input name="new_belong_class_name" type="text" id="new_belong_class_name" size="30" maxlength="255" value="<?php echo $mgr->getRequestData('new_belong_class_name') ?>" /></td>
            <td align="center">-</td>
            <td align="center">[<a href="javascript:;" onclick="insertBelongClass();">追加</a>]</td>
            <td align="center">-</td>
          </tr>
<?php if (is_array($mgr->request['belong_class_name']) && count($mgr->request['belong_class_name']) > 0) { ?>
          <tbody class="listTab_TB" id="listTab_TB">
<?php   foreach ($mgr->request['belong_class_name'] AS $id => $data) { ?>
          <tr id="<?php echo $id; ?>">
            <td align="center" class="SortTypeCell">&nbsp;</td>
            <td><input name="belong_class_name[<?php echo $id ?>]" type="text" id="belong_class_name_<?php echo $id ?>" value="<?php echo $mgr->getRequestData('belong_class_name', $id) ?>" size="30" maxlength="255" /></td>
            <td align="center">[<a href="javascript:;" onclick="goBelongDivMst(<?php echo $id ?>);">所属部門一覧</a>]</td>
            <td align="center">[<a href="javascript:;" onclick="updateBelongClass(<?php echo $id ?>);">更新</a>]</td>
            <td align="center">[<a href="javascript:;" onclick="deleteBelongClass(<?php echo $id ?>);">削除</a>]</td>
          </tr>
<?php     } ?>
          </tbody>
<?php   } ?>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll2"><input type="button" value="　　　全て更新　　　" onclick="mstEditAll();"></div></td>
          </tr>
        </table>
        <table border="0" cellpadding="2" cellspacing="0" class="AjaxTableDisable" id="nowLoad">
          <tr>
            <td><img src="./image/loading.gif" alt="並び替え中です・・・" /></td>
          </tr>
          <tr>
            <td>並び替え中です・・・</td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>


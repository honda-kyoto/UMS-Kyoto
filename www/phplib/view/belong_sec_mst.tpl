<?php include("view/head.tpl") ?>
      <input type="hidden" name="belong_class_id" id="belong_class_id" value="<?php echo $mgr->getRequestData('belong_class_id') ?>">
      <input type="hidden" name="belong_div_id" id="belong_div_id" value="<?php echo $mgr->getRequestData('belong_div_id') ?>">
      <input type="hidden" name="org_belong_dep_id" id="org_belong_dep_id" value="<?php echo $mgr->getRequestData('belong_dep_id') ?>">
      <input type="hidden" name="belong_sec_id" id="belong_sec_id" value="">
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　マスタメンテナンスに戻る　　　" onClick="location.href='msts.php'" /></div></td>
          </tr>
        </table>
        <h3>■『<?php echo $mgr->getOutputData('belong_dep_name') ?>』の所属課一覧</h3>

        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td width="120px">
		所属部変更
            </td>
            <td>
		<select name="belong_dep_id" id="belong_dep_id" onchange="onChangeSelect(this);">
                    <?php echo $mgr->getSelectList('belong_dep_id') ?>
                </select>
            </td>
          </tr>
        </table>

        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　全て更新　　　" onclick="mstEditAll();"></div></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab" id="listTab">
          <tr class="nodrop nodrag">
            <th scope="col" width="60">表示順</th>
            <th scope="col" width="60">部変更</th>
            <th scope="col">所属課名</th>
            <th scope="col" width="80">所属係</th>
            <th scope="col" width="50">更新</th>
            <th scope="col" width="50">削除</th>
          </tr>
          <tr class="nodrop nodrag">
            <td align="center">（新規）</td>
            <td align="center">&nbsp;</td>
            <td><input name="new_belong_sec_name" type="text" id="new_belong_sec_name" size="30" maxlength="255" value="<?php echo $mgr->getRequestData('new_belong_sec_name') ?>" /></td>
            <td align="center">-</td>
            <td align="center">[<a href="javascript:;" onclick="insertBelongSec();">追加</a>]</td>
            <td align="center">-</td>
          </tr>
<?php if (is_array($mgr->request['belong_sec_name']) && count($mgr->request['belong_sec_name']) > 0) { ?>
          <tbody class="listTab_TB" id="listTab_TB">
<?php   foreach ($mgr->request['belong_sec_name'] AS $id => $data) { ?>
          <tr id="<?php echo $id; ?>">
            <td align="center" class="SortTypeCell">&nbsp;</td>
            <td align="center"><a href="javascript:;"><input name="belong_sec_checkbox[<?php echo $id ?>]" type="checkbox" id="belong_sec_checkbox_<?php echo $id ?>" value="<?php echo $id ?>" /></a></td>
            <td><input name="belong_sec_name[<?php echo $id ?>]" type="text" id="belong_sec_name_<?php echo $id ?>" value="<?php echo $mgr->getRequestData('belong_sec_name', $id) ?>" size="30" maxlength="255" /></td>
            <td align="center">[<a href="javascript:;" onclick="goBelongChgMst(<?php echo $id ?>);">所属係一覧</a>]</td>
            <td align="center">[<a href="javascript:;" onclick="updateBelongSec(<?php echo $id ?>);">更新</a>]</td>
            <td align="center">[<a href="javascript:;" onclick="deleteBelongSec(<?php echo $id ?>);">削除</a>]</td>
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
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td>
              <input type="button" value="　　部一覧に戻る　　" onclick="returnDep();" />
            </td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>


<?php include("view/head.tpl") ?>
      <input type="hidden" name="vlan_ridge_id" id="vlan_ridge_id" value="">
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　マスタメンテナンスに戻る　　　" onClick="location.href='msts.php'" /></div></td>
          </tr>
        </table>
        <h3>■棟屋一覧</h3>
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　全て更新　　　" onclick="mstEditAll();"></div></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab" id="listTab">
          <tr class="nodrop nodrag">
            <th scope="col" width="60">表示順</th>
            <th scope="col">棟屋名</th>
            <th scope="col">階一覧</th>
            <th scope="col">更新</th>
            <th scope="col">削除</th>
          </tr>
          <tr class="nodrop nodrag">
            <td align="center">（新規）</td>
            <td><input name="new_vlan_ridge_name" type="text" id="new_vlan_ridge_name" size="30" maxlength="255" value="<?php echo $mgr->getRequestData('new_vlan_ridge_name') ?>" /></td>
            <td align="center">-</td>
            <td align="center">[<a href="javascript:;" onclick="insertRidge();">追加</a>]</td>
            <td align="center">-</td>
          </tr>
<?php if (is_array($mgr->request['vlan_ridge_name']) && count($mgr->request['vlan_ridge_name']) > 0) { ?>
          <tbody class="listTab_TB" id="listTab_TB">
<?php   foreach ($mgr->request['vlan_ridge_name'] AS $id => $data) { ?>
          <tr id="<?php echo $id; ?>">
            <td align="center" class="SortTypeCell">&nbsp;</td>
            <td><input name="vlan_ridge_name[<?php echo $id ?>]" type="text" id="vlan_ridge_name_<?php echo $id ?>" value="<?php echo $mgr->getRequestData('vlan_ridge_name', $id) ?>" size="30" maxlength="255" /></td>
            <td width="80" align="center">[<a href="javascript:;" onclick="goFloorMst(<?php echo $id ?>);">階一覧</a>]</td>
            <td width="50" align="center">[<a href="javascript:;" onclick="updateRidge(<?php echo $id ?>);">更新</a>]</td>
            <td width="50" align="center">[<a href="javascript:;" onclick="deleteRidge(<?php echo $id ?>);">削除</a>]</td>
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


<?php include("view/head.tpl") ?>
      <input type="hidden" name="vlan_floor_id" id="vlan_floor_id" value="<?php echo $mgr->getRequestData('vlan_floor_id') ?>">
      <input type="hidden" name="vlan_room_id" id="vlan_room_id" value="<?php echo $mgr->getRequestData('vlan_room_id') ?>">
      <input type="hidden" name="vlan_id" id="vlan_id" value="">
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　マスタメンテナンスに戻る　　　" onClick="location.href='msts.php'" /></div></td>
          </tr>
        </table>
        <h3>■『<?php echo $mgr->getOutputData('vlan_room_name') ?>』のVLAN一覧</h3>
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　全て更新　　　" onclick="mstEditAll();"></div></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab" id="listTab">
          <tr class="nodrop nodrag">
            <th scope="col" width="60">表示順</th>
            <th scope="col">VLAN名</th>
            <th scope="col">管理者表示名</th>
            <th scope="col">VLAN管理者</th>
            <th scope="col">管理者設定</th>
            <th scope="col">更新</th>
            <th scope="col">削除</th>
          </tr>
          <tr class="nodrop nodrag">
            <td align="center">（新規）</td>
            <td><input name="new_vlan_name" type="text" id="new_vlan_name" size="10" maxlength="3" value="<?php echo $mgr->getRequestData('new_vlan_name') ?>" /></td>
            <td><input name="new_admin_name" type="text" id="new_admin_name" size="20" maxlength="255" value="<?php echo $mgr->getRequestData('new_admin_name') ?>" /></td>
            <td align="center">-</td>
            <td align="center">-</td>
            <td align="center">[<a href="javascript:;" onclick="insertVlan();">追加</a>]</td>
            <td align="center">-</td>
          </tr>
<?php if (is_array($mgr->request['vlan_name']) && count($mgr->request['vlan_name']) > 0) { ?>
          <tbody class="listTab_TB" id="listTab_TB">
<?php   foreach ($mgr->request['vlan_name'] AS $id => $data) { ?>
          <tr id="<?php echo $id; ?>">
            <td align="center" class="SortTypeCell">&nbsp;</td>
            <td><input name="vlan_name[<?php echo $id ?>]" type="text" id="vlan_name_<?php echo $id ?>" value="<?php echo $mgr->getRequestData('vlan_name', $id) ?>" size="10" maxlength="3" /></td>
            <td><input name="admin_name[<?php echo $id ?>]" type="text" id="admin_name_<?php echo $id ?>" value="<?php echo $mgr->getRequestData('admin_name', $id) ?>" size="20" maxlength="255" /></td>
            <td><?php echo nl2br($mgr->getOutputData('vlan_admin_list', $id)) ?>&nbsp;</td>
            <td width="80" align="center">[<a href="javascript:;" onclick="goAdminList(<?php echo $id ?>);">管理者設定</a>]</td>
            <td width="50" align="center">[<a href="javascript:;" onclick="updateVlan(<?php echo $id ?>);">更新</a>]</td>
            <td width="50" align="center">[<a href="javascript:;" onclick="deleteVlan(<?php echo $id ?>);">削除</a>]</td>
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
              <input type="button" value="　　階一覧に戻る　　" onclick="returnRoom();" />
            </td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>


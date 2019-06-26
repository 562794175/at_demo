<?php
require_once 'function.php';
require_once 'page.class.php';
$db = getDBConn();
$sqlcount = "select count(*) from xau_bolling";
$resultall = $db->query($sqlcount);
$arr1 = $resultall->fetch_row();
$c = $arr1[0];//用一个变量获取这个数组的值
$page = new page($c,100);//一共多少条 每页显示多少条
$sql = "select *  from xau_bolling " .$page->limit;
$result = $db->query($sql);

$one_count=getTypeCount(1);
$two_count=getTypeCount(2);
$three_count=getTypeCount(3);
$four_count=getTypeCount(4);

echo "<div align='center'>S:".$one_count.",K:".$two_count.",D:".$three_count.",Z:".$four_count."</div>";


echo "<div align='center'>{$page->fpage()}</div>";//显示分页信息
?>
    <table align="center" width="30%" style="text-align:center;" border="1">
        <tr>
            <td>序号</td>
            <td>图片</td>
            <td>人工</td>
            <td>SVM</td>
            <td>操作</td>
        </tr>
<?php
    $linear_right_class=0;
    $linear_right_key=0;
    
    if($result){
        $arr = $result->fetch_all();
        foreach($arr as $k => $v){
            $id=$v[0];
            $bolling['l']= explode(',', $v[1]);
            $bolling['m']=explode(',', $v[2]);
            $bolling['u']=explode(',', $v[3]);
            $learn_type=$v[4];
            $human_type=$v[5];
            $file=getBollingPng($id,$bolling);
            if($human_type>0) {
                echo"<tr bgcolor='#f0f0f0'>";
            } else {
                echo"<tr>";
            }
            
            
            echo"<td>".$id."</td>";
            echo"<td><img src='".$file."'></td>";
            
            //预测
            $atmp= array_merge($bolling['l'],$bolling['m']);
            $atmp= array_merge($atmp,$bolling['u']);
            $svm_type=predict($atmp,$human_type,'/model.linear.svm');
            //准确率
            if($svm_type[1]==1) $linear_right_class++;
            if($svm_type[2]==1) $linear_right_key++;
            
            $sType="human_type_".$id;
            echo"<td><input type='radio' name='".$sType."' onclick='humanclick(".$id.",1)' ".getChecked(1,$human_type)." title='两边同时收缩'>S";
            echo"<input type='radio' name='".$sType."' onclick='humanclick(".$id.",2)' ".getChecked(2,$human_type)." title='两边同时扩张'>K";
            echo"<input type='radio' name='".$sType."' onclick='humanclick(".$id.",3)' ".getChecked(3,$human_type)." title='两边同一个方向(不平行)'>D";
            echo"<input type='radio' name='".$sType."' onclick='humanclick(".$id.",4)' ".getChecked(4,$human_type)." title='两边平行或(一边平一边缩或扩)'>Z</td>";
            
            //echo"<td>".$svm_type[0]."</td>";
            echo"<td></td>";
            
            echo"<td>0</td>";
            echo"</tr>";
            unset($bolling);
        }
        echo "<div align='center'>linear class:".$linear_right_class.",key:".$linear_right_key."</div>";
    }
    
    
?>
    </table>
    <br />
<?php
    echo "<div align='center'>{$page->fpage()}</div>";//显示分页信息
?>
<script type="text/javascript" src="jquery-1.12.4.js"></script>
<script type="text/javascript">
function humanclick(id,type)
{
    htmlobj=$.ajax({url:"humantype.php?id="+id+"&type="+type,async:false});
    //alert(htmlobj.responseText);
}
</script>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" href="jquery/jquery-ui.css">
<style>
#datepicker {
    width:85px;
}
.ui-selectmenu-button.ui-button{
    width:70px;
}
#tabs .ui-accordion .ui-accordion-content {
    padding:0.5em;
}
.ui-controlgroup {
    vertical-align: baseline;
}
.ui-controlgroup .ui-selectmenu-button {
    width:80px;
}
.ui-controlgroup label {
    padding: 5px;
}
.ui-tabs .ui-tabs-nav li {
    width:53px;
}
.ui-tabs .ui-tabs-panel {
    padding:.1em .1em .1em .1em;
}
</style>
</head>
<body>
    <?php 
        require_once 'function.php'; 
        $sToday=empty($_GET["date"])?date("Y-m-d",time()):$_GET["date"];
        $sPeroid=empty($_GET["peroid"])?0:$_GET["peroid"];
        $sPeroidName=getPeroid($sPeroid);
        $sTime=empty($_GET["time"])?0:$_GET["time"];
        $sTimeName=getPeroidTime('1H',$sTime,false);
        $sStateCode=empty($_GET["state"])?0:$_GET["state"];
        $sChange=empty($_GET["change"])?0:$_GET["change"];
        
        //查询数据库
        $aRecord= getRecord($sToday, $sTimeName);
        $nRecordID=empty($aRecord['id'])?0:$aRecord['id'];
        if($nRecordID<=0) {
            //没有就插入
            insertRecord($sToday,$sTimeName);
            $aRecord= getRecord($sToday, $sTimeName);
            $nRecordID=empty($aRecord['id'])?0:$aRecord['id'];
            //设置默认值
            $nInitStateCode=11;
            updateRecordTargetState($nRecordID,'1H',$nInitStateCode);
            updateRecordTargetState($nRecordID,'4H',$nInitStateCode);
            updateRecordTargetState($nRecordID,'1D',$nInitStateCode);
        }
        //改变状态
        if((int)$sChange===1 && $nRecordID>0) {
            updateRecordTargetState($nRecordID,$sPeroidName,$sStateCode);
        }
        //改变状态后再取，不然UI会没有变化
        $aRecordTarget=getRecordTarget($nRecordID);
        $sTimeOption=getPeroidTime('1H',(int)$sTime);
        $sH1StateOption='';
        $sH4StateOption='';
        $sD1StateOption='';
        foreach ($aRecordTarget as $key => $value) {
            if($value['peroid']=='1H') $sH1StateOption=getPeroidState((int)$value['state_code']);
            if($value['peroid']=='4H') $sH4StateOption=getPeroidState((int)$value['state_code']);
            if($value['peroid']=='1D') $sD1StateOption=getPeroidState((int)$value['state_code']);
        }
        
    ?>
    <div class="widget">
        <div class="controlgroup">
                <label class="ui-widget ui-controlgroup-item">日期：</label>
                <input readonly="readonly" id="datepicker" class="ui-widget ui-controlgroup-item ui-button" value="<?php echo $sToday; ?>">
                <label class="ui-widget ui-controlgroup-item">时间：</label>
                <select id="peroidtime">
                  <?php echo $sTimeOption; ?>
                </select>
        </div>
        <div id="tabs">
            <ul>
              <li><a href="#tabs-1">1D</a></li>
              <li><a href="#tabs-2">4H</a></li>
              <li><a href="#tabs-3">1H</a></li>
              
            </ul>
            <div id="tabs-1">
                <image src="pic1.png" width="100%" border="0">
                <div class="ui-state-highlight ui-corner-all" style="padding: 3px; height:30px; ">
                    <span class="ui-icon ui-icon-unlocked" style="float: left;margin-right: .3em;"></span>
                    <strong>状态</strong>
                    <select id='h1state'>
                        <?php echo $sD1StateOption; ?>
                    </select>
                </div>
                
                <div id="accordion">
                    <h3>Section 1</h3>
                    <div>
                        <p>【<a href="edit.php">编辑</a>】【<a href="edit.php">删除</a>】
                      Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
                      ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
                      amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
                      odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
                      </p>
                      <p>
                          <image src="pic2.png" width="100px" height="80px" border="1">
                          <image src="pic2.png" width="100px" height="80px" border="1">
                      </p>
                    </div>
                    <h3>Section 2</h3>
                    <div>
                      <p>【<a href="edit.php">编辑</a>】【<a href="edit.php">删除</a>】
                      Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
                      purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
                      velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
                      suscipit faucibus urna.
                      </p>
                    </div>
                    <h3>Section 3</h3>
                    <div>
                      <p>【<a href="edit.php">编辑</a>】【<a href="edit.php">删除</a>】
                      Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
                      Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
                      ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
                      lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
                      </p>
                      <ul>
                        <li>List item one</li>
                        <li>List item two</li>
                        <li>List item three</li>
                      </ul>
                    </div>
                    <h3>Section 4</h3>
                    <div>
                      <p>【<a href="edit.php">编辑</a>】【<a href="edit.php">删除</a>】
                      Cras dictum. Pellentesque habitant morbi tristique senectus et netus
                      et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
                      faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
                      mauris vel est.
                      </p>
                      <p>
                      Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
                      Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                      inceptos himenaeos.
                      </p>
                    </div>
                  </div>

            </div>
            <div id="tabs-2">

                <image src="pic1.png" width="100%" border="0">
                <div class="ui-state-highlight ui-corner-all" style="padding: 3px; height:30px; ">
                    <span class="ui-icon ui-icon-unlocked" style="float: left;margin-right: .3em;"></span>
                    <strong>状态</strong>
                    <select id='h4state'>
                        <?php echo $sH4StateOption; ?>
                    </select>
                </div>
                
                <div id="accordiontwo">
                    <h3>Section 5</h3>
                    <div>
                        <p>【<a href="edit.php">编辑</a>】【<a href="edit.php">删除</a>】
                        Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
                        ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
                        amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
                        odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
                        </p>
                        <p>
                          <image src="pic2.png" width="100px" height="80px" border="1">
                          <image src="pic2.png" width="100px" height="80px" border="1">

                        </p>
                    </div>
                    <h3>Section 6</h3>
                    <div>
                      <p>【<a href="edit.php">编辑</a>】【<a href="edit.php">删除</a>】
                      Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
                      purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
                      velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
                      suscipit faucibus urna.
                      </p>
                    </div>
                </div>
            </div>
            <div id="tabs-3">
                <image src="pic1.png" width="100%" border="0">
                <div class="ui-state-highlight ui-corner-all" style="padding: 3px; height:30px; ">
                    <span class="ui-icon ui-icon-unlocked" style="float: left;margin-right: .3em;"></span>
                    <strong>状态</strong>
                    <select id='d1state'>
                        <?php echo $sH1StateOption; ?>
                    </select>
                </div>
                
                <div id="accordionthree">
                    <h3>Section 7</h3>
                    <div>
                        <p>【<a href="edit.php">编辑</a>】【<a href="edit.php">删除</a>】
                        Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
                        ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
                        amet, nunc. 
                        </p>
                        <p>
                          <image src="pic2.png" width="100px" height="80px" border="1">
                          <image src="pic2.png" width="100px" height="80px" border="1">

                        </p>
                    </div>
                </div>
                
                
                
            </div>
        </div>
</div>
    
    
<script src="jquery/jquery-1.12.4.js"></script>
<script src="jquery/jquery-ui.js"></script>
<script>
$( function() {
    $( ".controlgroup" ).controlgroup({"direction": "horizontal"});
    $( "#datepicker" ).datepicker({"dateFormat":"yy-mm-dd"});
    $( "#tabs" ).tabs({active:<?php echo $sPeroid;?>});
    $( "#accordion" ).accordion({heightStyle: "content"});
    $( "#accordiontwo" ).accordion({heightStyle: "content"});
    $( "#accordionthree" ).accordion({heightStyle: "content"});
    $( "#peroidtime" ).selectmenu({
      change: function( event, data ) {
        var date=$( "#datepicker" ).val();
        window.location.href='?date='+date+'&time='+data.item.value;
      }
    });
    $( "#h1state" ).selectmenu({
      change: function( event, data ) {
        var date=$( "#datepicker" ).val();
        var time=$( "#peroidtime" ).val();
        window.location.href='?date='+date+'&time='+time+'&change=1&peroid=0&state='+data.item.value;
      }
    });
    $( "#h4state" ).selectmenu({
      change: function( event, data ) {
        var date=$( "#datepicker" ).val();
        var time=$( "#peroidtime" ).val();
        window.location.href='?date='+date+'&time='+time+'&change=1&peroid=1&state='+data.item.value;
      }
    });
    $( "#d1state" ).selectmenu({
      change: function( event, data ) {
        var date=$( "#datepicker" ).val();
        var time=$( "#peroidtime" ).val();
        window.location.href='?date='+date+'&time='+time+'&change=1&peroid=2&state='+data.item.value;
      }
    });
} );
</script>

    
</body>
</html>

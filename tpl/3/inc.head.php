<div class="head">
    <div class="head-box">
        <div class="head-box-logo sprite-logo"></div>
        <div class="head-box-text">
            <div class="sprite-colorfont"></div>
            <div class="head-text"><?php echo $_TPL_HT; ?></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="sprite-line-colord">
    <?php if ($_TPL_PANNEL) { ?>
        <div class="server-on"></div>
        <!--[控制器]-->
        <div class="server-click sprite-exbtn" onclick="PanelToggle();"></div>
        <!--[菜单]-->
        <div class="server-bgx">
            <div class="server-bg">
                <div class="server-panel sprite-pennel">
                    <!--[可选项]-->
                    <div class="server-model">
                        <span style="position: relative;margin-left: 10px;margin-right: 10px;font-size: 20px;top: 4px;font-weight:300;">Log/Rt.</span>
                        <div class="switch" id="modelc" data-on-label="Log" data-off-label="Rt" data-off="warning" data-on="success">
                            <input type="checkbox" checked />
                        </div>
                    </div>

                    <!--[服务器列表]-->
                    <div class="server-list-box">
                        <?php if(count($SERVER)>0){foreach($SERVER as $server){?>
                        <div class="server-one" id="active-<?php echo $server['dir'];?>" onclick="Chatx.setTarget('<?php echo $server['dir'];?>').setLabel('<?php echo $server['name'];?>').draw();" title="<?php echo $server['name'];?>">
                            <div class="sprite-server server-one-li"></div>
                            <span style="color: #8F8F8F;"><?php echo $server['name'];?></span>
                        </div>
                        <div class="clearfix"></div>
                        <?php }} ?>
                    </div>
                    
                    <!--[退出按钮]-->
                    <div class="server-bottom">
                        <a href="login.php?action=logout">
                            <div class="sprite-logout" style="float: right;"></div>
                            <div style="float: right;font-weight:300;color: #8F8F8F;font-size: 18px;">Logout&nbsp;&nbsp;</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
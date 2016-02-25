<div class="block">
    <div>
        <div class="list2">
            <h2 class="fillet_all color2a">Login</h2>
        </div>
        <div class="clearfix"></div>
        <p class="lead"><a href="http://travel.olc.tw" title="就愛玩" style="color: #C00;">就愛玩</a> is a website that focus on making travel itineraries.<br />
            You could share your travel itineraries here and interact with your friends through itineraries and points.</p>
    </div>
    <div class="fields_s">
        <?php echo $this->Form->create('Member', array('action' => 'login', 'class' => 'form-inline')); ?>
        <dl class="list4">
            <dt class="bg_gary1">Login information</dt>
            <dd style="padding: 40px;">
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">Username</span>
                        <?php
                        echo $this->Form->input('Member.username', array(
                            'type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'span2',
                            'tabindex' => 1,
                        ));
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">Password</span>
                        <?php
                        echo $this->Form->input('Member.password', array(
                            'type' => 'password',
                            'label' => false,
                            'div' => false,
                            'class' => 'span2',
                            'tabindex' => 1,
                        ));
                        ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="line">
                    <?php
                    echo $this->Form->button('Login', array('type' => 'submit', 'class' => 'btn btn-primary'));
                    echo ' &nbsp; ' . $this->Html->link('Forgot password?', '/members/passwordForgotten/', array('class' => 'btn'));
                    ?>
                </div>
            </dd>
            <hr />
            <span class="span1">Fast login:</span>
            <div class="btn-group">
                <?php
                echo $this->Html->link('Facebook', '/auth/facebook', array('class' => 'btn span1'));
                echo $this->Html->link('Google', '/auth/google', array('class' => 'btn span1'));
                ?>
            </div>
            <div class="btn-group">
                <?php
                echo $this->Html->link('Flickr', '/auth/flickr', array('class' => 'btn span1'));
                echo $this->Html->link('GitHub', '/auth/github', array('class' => 'btn span1'));
                echo $this->Html->link('LinkedIn', '/auth/linkedin', array('class' => 'btn span1'));
                ?>
            </div>
        </dl>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="fields_c">
        <div class="title2">
            <h2 class="spot spot_profile float-l">Sign up as the member of our website</h2>
            <div class="clearfix"></div>
        </div>
        <div class="list1">
            <ul class="table">
                <li class="dTable"><span class="table_td1">Itineraries: </span>Browse the itineraries shared by members and import them into your one.</li>
                <li class="dTable"><span class="table_td1">Points:</span>Browse our points, or add some points to extend your travel map</li>
                <li class="dTable"><span class="table_td1">Areas:</span>Browse itineraries, points and members based on specified area.</li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <?php
        echo $this->Html->link('Signup', '/members/signup/', array('class' => 'btn btn-primary btn-large'));
        ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<?php echo form_open('/contacts'); ?>

    <div class="form-group">
        <?php echo form_label('Email', 'email'); ?>
        <?php echo form_input(array(
            'name' => 'email',
            'placeholder' => 'Укажите ваш email',
            'class' => 'form-control',
            'id' => 'contact-email',
            'value' => set_value('email')
        )); ?>
        <?php echo form_error('email', '<div style="color: coral">', '</div>'); ?>
    </div>

    <div class="form-group">
        <?php echo form_label('Тема', 'subject'); ?>
        <?php echo form_input(array(
            'name' => 'subject',
            'placeholder' => 'Укажите тему письма',
            'class' => 'form-control',
            'id' => 'contact-subject',
            'value' => set_value('subject')
        )); ?>
        <?php echo form_error('subject', '<div style="color: coral">', '</div>'); ?>
    </div>
    <div class="form-group">
        <?php echo form_label('Введите каптчу', 'captcha'); ?>
        <p><?php echo $image ?></p>
        <?php echo form_input(array(
            'name' => 'captcha',
            'class' => 'form-control',
            'id' => 'contact-captcha'
        )); ?>
        <br />
        <br />
        <?php echo form_error('captcha', '<div style="color: coral">', '</div>'); ?>
    </div>
    <div class="form-group">
        <?php echo form_label('Текст письма', 'message'); ?>
        <?php echo form_textarea(array(
            'name' => 'message',
            'placeholder' => 'Укажите текст письма',
            'class' => 'form-control',
            'id' => 'contact-message',
            'value' => set_value('message')
        )); ?>
        <br />
        <br />
        <?php echo form_error('message', '<div style="color: coral">', '</div>'); ?>
    </div>



    <button type="submit" class="btn btn-default">Отправить</button>


<? echo form_close(); ?>
<?php
if(!defined('ABSPATH')) exit;

/**
 * Поле иконки в терминах таксономии городов
 */
class MSTS_Term_Icons {
    private static $inst;
    public static function instance(){return self::$inst?:self::$inst=new self();}
    private function __construct(){
        $tax=MSTS_Settings::instance()->get()['city_taxonomy'];
        add_action($tax.'_add_form_fields',[$this,'add']);
        add_action($tax.'_edit_form_fields',[$this,'edit']);
        add_action('created_'.$tax,[$this,'save']);
        add_action('edited_'.$tax,[$this,'save']);
    }
    public function add(){ ?>
        <div class="form-field">
            <label for="msts_icon">Иконка</label>
            <input type="text" name="msts_icon" id="msts_icon" value="" placeholder="😎">
            <p>Эмодзи / короткий текст / URL.</p>
        </div><?php
    }
    public function edit($term){
        $icon=get_term_meta($term->term_id,'msts_icon',true); ?>
        <tr class="form-field">
            <th><label for="msts_icon">Иконка</label></th>
            <td><input type="text" name="msts_icon" id="msts_icon" value="<?php echo esc_attr($icon);?>" placeholder="😎">
            <p class="description">Эмодзи / текст / URL картинки.</p></td>
        </tr><?php
    }
    public function save($term_id){
        if(isset($_POST['msts_icon'])){
            update_term_meta($term_id,'msts_icon',sanitize_text_field($_POST['msts_icon']));
        }
    }
}
<?php
/**
 * Gift Metabox for WooCommerce Products
 * Allows admins to attach gift files to products
 *
 * @package MySuperTour Product Icons
 * @author Telegram @l1ghtsun
 */

if (!defined('ABSPATH')) exit;

class MST_Gift_Metabox {
    
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_gift_metabox']);
        add_action('save_post_product', [$this, 'save_gift_metabox']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_media_uploader']);
    }
    
    /**
     * Add gift metabox to product edit screen
     */
    public function add_gift_metabox() {
        add_meta_box(
            'mst_gift_metabox',
            '🎁 Подарок для покупателя',
            [$this, 'render_gift_metabox'],
            'product',
            'side',
            'default'
        );
    }
    
    /**
     * Render gift metabox content
     */
    public function render_gift_metabox($post) {
        wp_nonce_field('mst_gift_nonce_action', 'mst_gift_nonce');
        
        $gift_id = get_post_meta($post->ID, '_mst_gift_file', true);
        $gift_url = $gift_id ? wp_get_attachment_url($gift_id) : '';
        $gift_filename = $gift_id ? basename($gift_url) : 'Не выбран';
        
        ?>
        <div class="mst-gift-metabox-wrapper">
            <p style="margin-bottom: 10px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Файл подарка (PDF, ZIP, и т.д.)
                </label>
                <input type="hidden" name="mst_gift_file" id="mst_gift_file" value="<?php echo esc_attr($gift_id); ?>">
                <button type="button" class="button" id="mst_upload_gift" style="margin-bottom: 8px;">
                    📁 Выбрать файл
                </button>
                <button type="button" class="button" id="mst_remove_gift" style="margin-bottom: 8px; <?php echo !$gift_id ? 'display:none;' : ''; ?>">
                    ❌ Удалить
                </button>
            </p>
            <p id="mst_gift_filename" style="margin: 0; padding: 10px; background: #f0f0f0; border-radius: 4px; font-size: 13px; word-break: break-all;">
                <?php echo esc_html($gift_filename); ?>
            </p>
            <?php if ($gift_url): ?>
            <p style="margin-top: 10px;">
                <a href="<?php echo esc_url($gift_url); ?>" target="_blank" class="button button-small">
                    👁️ Просмотр
                </a>
            </p>
            <?php endif; ?>
        </div>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var fileFrame;
            
            $('#mst_upload_gift').on('click', function(e) {
                e.preventDefault();
                
                if (fileFrame) {
                    fileFrame.open();
                    return;
                }
                
                fileFrame = wp.media({
                    title: 'Выберите файл подарка',
                    button: {
                        text: 'Использовать этот файл'
                    },
                    multiple: false
                });
                
                fileFrame.on('select', function() {
                    var attachment = fileFrame.state().get('selection').first().toJSON();
                    $('#mst_gift_file').val(attachment.id);
                    $('#mst_gift_filename').text(attachment.filename);
                    $('#mst_remove_gift').show();
                });
                
                fileFrame.open();
            });
            
            $('#mst_remove_gift').on('click', function(e) {
                e.preventDefault();
                $('#mst_gift_file').val('');
                $('#mst_gift_filename').text('Не выбран');
                $(this).hide();
            });
        });
        </script>
        <?php
    }
    
    /**
     * Save gift metabox data
     */
    public function save_gift_metabox($post_id) {
        // Check nonce
        if (!isset($_POST['mst_gift_nonce']) || !wp_verify_nonce($_POST['mst_gift_nonce'], 'mst_gift_nonce_action')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save gift file ID
        if (isset($_POST['mst_gift_file'])) {
            $gift_id = intval($_POST['mst_gift_file']);
            if ($gift_id > 0) {
                update_post_meta($post_id, '_mst_gift_file', $gift_id);
            } else {
                delete_post_meta($post_id, '_mst_gift_file');
            }
        }
    }
    
    /**
     * Enqueue media uploader scripts
     */
    public function enqueue_media_uploader($hook) {
        if ($hook === 'post.php' || $hook === 'post-new.php') {
            global $post_type;
            if ($post_type === 'product') {
                wp_enqueue_media();
            }
        }
    }
}

// Initialize the gift metabox
new MST_Gift_Metabox();

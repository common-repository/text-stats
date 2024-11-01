<?php
/*
Plugin Name: Text Stats
Plugin URI:
Description: Text Statistics shows several statistics to posts: Flesch-Kincaid Reading Ease, Flesch-Kincaid Grade Level, Gunning Fog Score, Coleman Liau Index, Smog Index, Automated Readability Index... it uses the amazing class Text Statistics developed by David Child (https://github.com/DaveChild/Text-Statistics)
Author: Rafael Matito
Author URI:
Version: 1.0
*/ 

//-------------------------------------
// Defines
//-------------------------------------

define('TEXTSTATISTICSFILE', ABSPATH . 'wp-content/plugins/textstats/TextStatistics/TextStatistics.php');

//-------------------------------------
// Main plugin class
//-------------------------------------

class TextStats {    
    private $exists_file = FALSE;

    public function __construct() {
        if (file_exists(TEXTSTATISTICSFILE)) {
            $this->exists_file = TRUE;
            require_once(TEXTSTATISTICSFILE);
        }
    }

    public function add_box_statistics() {
        add_meta_box('text_stats_info', __( 'Text Statistics'), array(&$this, 'render_box_statistics_content'),
             'post', 'advanced', 'high');
    }

    public function render_box_statistics_content($post) {
        if ($this->exists_file) {
            if ($post->post_content) {
                $statistics = new TextStatistics;
                $text = $post->post_content;

                //Output HTML
                print '<ul class="clearfix">';
                    print '<li><span class="label">' . __('Flesch-Kincaid Reading Ease:') . '</span>&nbsp;<span class="value">' . $statistics->flesch_kincaid_reading_ease($text).'</span></li>';
                    print '<li><span class="label">' . __('Flesch-Kincaid Grade Level:') . '</span>&nbsp;<span class="value">' . $statistics->flesch_kincaid_grade_level($text).'</span></li>';
                    print '<li><span class="label">' . __('Gunning Fog Score:') . '</span>&nbsp;<span class="value">' . $statistics->gunning_fog_score($text).'</span></li>';
                    print '<li><span class="label">' . __('Coleman Liau Index:') . '</span>&nbsp;<span class="value">' . $statistics->coleman_liau_index($text).'</span></li>';
                    print '<li><span class="label">' . __('Smog Index:') . '</span>&nbsp;<span class="value">' . $statistics->smog_index($text).'</span></li>';
                    print '<li><span class="label">' . __('Automated Readability Index:') . '</span>&nbsp;<span class="value">' . $statistics->automated_readability_index($text).'</span></li>';
                    print '<li><span class="label">' . __('Text Length:') . '</span>&nbsp;<span class="value">' . $statistics->text_length($text).'</span></li>';
                    print '<li><span class="label">' . __('Letter Count:') . '</span>&nbsp;<span class="value">' . $statistics->letter_count($text).'</span></li>';
                    print '<li><span class="label">' . __('Sentence Count:') . '</span>&nbsp;<span class="value">' . $statistics->sentence_count($text).'</span></li>';
                    print '<li><span class="label">' . __('Word Count:') . '</span>&nbsp;<span class="value">' . $statistics->word_count($text).'</span></li>';
                    print '<li><span class="label">' . __('Average Words Per Sentence:') . '</span>&nbsp;<span class="value">' . $statistics->average_words_per_sentence($text).'</span></li>';
                    print '<li><span class="label">' . __('Total Syllables:') . '</span>&nbsp;<span class="value">' . $statistics->total_syllables($text).'</span></li>';
                    print '<li><span class="label">' . __('Average Syllables Per Word:') . '</span>&nbsp;<span class="value">' . $statistics->average_syllables_per_word($text).'</span></li>';
                    print '<li><span class="label">' . __('Words With Three Syllables:') . '</span>&nbsp;<span class="value">' . $statistics->words_with_three_syllables($text).'</span></li>';
                    print '<li><span class="label">' . __('Percentage Words With Three Syllables:') . '</span>&nbsp;<span class="value">' . round($statistics->percentage_words_with_three_syllables($text), 2).' %</span></li>';
                print '</ul>';
            }
            else {
                print __('No content');
            }
        }
        else {
            print __('<span class="error">TextStatistics Class not found, please read readme.txt file</span>');
        }
    }

    public function add_css($hook) {
        if( 'post.php' != $hook ) {
            return;
        }
        else {
            wp_register_style('textstats', plugins_url('/textstats.css', __FILE__));
            wp_enqueue_style('textstats');
        }
    }
}

//-------------------------------------
// WP hooks and actions
//-------------------------------------

$stats = new TextStats;
add_action('add_meta_boxes', array($stats, 'add_box_statistics'));

//Add custom css
add_action('admin_enqueue_scripts', array($stats, 'add_css'));


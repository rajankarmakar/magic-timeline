<?php
namespace Magic_Timeline\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Timeline_Widget extends Widget_Base {

	public function get_name() {
		return 'magic-timeline';
	}

	public function get_title() {
		return __( 'Magic Timeline', 'magic-timeline-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	public function get_categories() {
		return array( 'magic-timeline' );
	}

	public function get_keywords() {
		return array( 'timeline', 'history', 'process', 'steps', 'vertical', 'changelog' );
	}

	public function get_style_depends() {
		return array( 'magic-timeline' );
	}

	public function get_script_depends() {
		return array( 'magic-timeline' );
	}

	protected function register_controls() {
		$this->register_content_layout_controls();
		$this->register_content_items_controls();
		$this->register_style_container_controls();
		$this->register_style_line_controls();
		$this->register_style_icon_controls();
		$this->register_style_date_controls();
		$this->register_style_title_controls();
		$this->register_style_badge_controls();
		$this->register_style_description_controls();
		$this->register_style_button_controls();
		$this->register_style_spacing_controls();
	}

	/* -----------------------------------------------------------------
	 * CONTENT: Layout
	 * --------------------------------------------------------------- */
	private function register_content_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'layout_direction',
			array(
				'label'        => __( 'Layout', 'magic-timeline-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'vertical',
				'options'      => array(
					'vertical'   => __( 'Single Column', 'magic-timeline-for-elementor' ),
					'alternate'  => __( 'Alternating', 'magic-timeline-for-elementor' ),
					'horizontal' => __( 'Horizontal', 'magic-timeline-for-elementor' ),
				),
				'prefix_class' => 'mtl-layout-',
			)
		);

		$this->add_control(
			'icon_shape',
			array(
				'label'        => __( 'Icon Badge Shape', 'magic-timeline-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'circle',
				'options'      => array(
					'circle' => __( 'Circle', 'magic-timeline-for-elementor' ),
					'square' => __( 'Square', 'magic-timeline-for-elementor' ),
				),
				'prefix_class' => 'mtl-icon-shape-',
			)
		);

		$this->add_control(
			'show_line',
			array(
				'label'        => __( 'Connecting Line', 'magic-timeline-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magic-timeline-for-elementor' ),
				'label_off'    => __( 'Hide', 'magic-timeline-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'enable_animation',
			array(
				'label'        => __( 'Entrance Animation', 'magic-timeline-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'magic-timeline-for-elementor' ),
				'label_off'    => __( 'Off', 'magic-timeline-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'disable_animation_mobile',
			array(
				'label'        => __( 'Disable Animation on Mobile', 'magic-timeline-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magic-timeline-for-elementor' ),
				'label_off'    => __( 'No', 'magic-timeline-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'enable_animation' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * CONTENT: Repeater items
	 * --------------------------------------------------------------- */
	private function register_content_items_controls() {
		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Timeline Items', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_icon',
			array(
				'label'   => __( 'Icon', 'magic-timeline-for-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'far fa-calendar-alt',
					'library' => 'fa-regular',
				),
			)
		);

		$repeater->add_control(
			'item_date',
			array(
				'label'       => __( 'Date', 'magic-timeline-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'January 1st, 2025', 'magic-timeline-for-elementor' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'       => __( 'Title', 'magic-timeline-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Timeline Title', 'magic-timeline-for-elementor' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'item_badge_show',
			array(
				'label'        => __( 'Status Badge', 'magic-timeline-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magic-timeline-for-elementor' ),
				'label_off'    => __( 'Hide', 'magic-timeline-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$repeater->add_control(
			'item_badge_text',
			array(
				'label'       => __( 'Badge Text', 'magic-timeline-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Latest', 'magic-timeline-for-elementor' ),
				'label_block' => true,
				'condition'   => array( 'item_badge_show' => 'yes' ),
			)
		);

		$repeater->add_control(
			'item_description',
			array(
				'label'       => __( 'Description', 'magic-timeline-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Describe what happened at this point in the timeline.', 'magic-timeline-for-elementor' ),
				'rows'        => 4,
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'item_button_show',
			array(
				'label'        => __( 'Button', 'magic-timeline-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magic-timeline-for-elementor' ),
				'label_off'    => __( 'Hide', 'magic-timeline-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$repeater->add_control(
			'item_button_text',
			array(
				'label'       => __( 'Button Text', 'magic-timeline-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Download ZIP', 'magic-timeline-for-elementor' ),
				'label_block' => true,
				'condition'   => array( 'item_button_show' => 'yes' ),
			)
		);

		$repeater->add_control(
			'item_button_icon',
			array(
				'label'     => __( 'Button Icon', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-download',
					'library' => 'fa-solid',
				),
				'condition' => array( 'item_button_show' => 'yes' ),
			)
		);

		$repeater->add_control(
			'item_button_url',
			array(
				'label'         => __( 'Button Link', 'magic-timeline-for-elementor' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'magic-timeline-for-elementor' ),
				'default'       => array(
					'url' => '#',
				),
				'show_external' => true,
				'condition'     => array( 'item_button_show' => 'yes' ),
			)
		);

		$this->add_control(
			'timeline_items',
			array(
				'label'       => __( 'Items', 'magic-timeline-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_title }}}',
				'default'     => array(
					array(
						'item_title'       => __( 'Initial Release', 'magic-timeline-for-elementor' ),
						'item_date'        => __( 'March 13th, 2025', 'magic-timeline-for-elementor' ),
						'item_badge_show'  => 'yes',
						'item_badge_text'  => __( 'Latest', 'magic-timeline-for-elementor' ),
						'item_description' => __( 'We officially launched the plugin with core timeline functionality and full Elementor style controls.', 'magic-timeline-for-elementor' ),
						'item_button_show' => 'yes',
						'item_button_text' => __( 'Download ZIP', 'magic-timeline-for-elementor' ),
					),
					array(
						'item_title'       => __( 'Beta Testing Phase', 'magic-timeline-for-elementor' ),
						'item_date'        => __( 'February 1st, 2025', 'magic-timeline-for-elementor' ),
						'item_description' => __( 'Gathered feedback from beta users and refined the widget based on real-world usage.', 'magic-timeline-for-elementor' ),
					),
					array(
						'item_title'       => __( 'Project Kickoff', 'magic-timeline-for-elementor' ),
						'item_date'        => __( 'December 10th, 2024', 'magic-timeline-for-elementor' ),
						'item_description' => __( 'Started development of the Magic Timeline widget with a focus on clean design and flexibility.', 'magic-timeline-for-elementor' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Container
	 * --------------------------------------------------------------- */
	private function register_style_container_controls() {
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => __( 'Container', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'container_max_width',
			array(
				'label'      => __( 'Max Width', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 1400,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-timeline' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mtl-timeline',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-timeline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'container_radius',
			array(
				'label'      => __( 'Border Radius', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-timeline' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_shadow',
				'selector' => '{{WRAPPER}} .mtl-timeline',
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Connecting line
	 * --------------------------------------------------------------- */
	private function register_style_line_controls() {
		$this->start_controls_section(
			'section_style_line',
			array(
				'label'     => __( 'Connecting Line', 'magic-timeline-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_line' => 'yes' ),
			)
		);

		$this->add_control(
			'line_color',
			array(
				'label'     => __( 'Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E4E7EC',
				'selectors' => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-line-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'line_width',
			array(
				'label'      => __( 'Width', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 12,
					),
				),
				'default'    => array(
					'size' => 2,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-line-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'line_style',
			array(
				'label'     => __( 'Style', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'magic-timeline-for-elementor' ),
					'dashed' => __( 'Dashed', 'magic-timeline-for-elementor' ),
					'dotted' => __( 'Dotted', 'magic-timeline-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-line-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'line_gap_color',
			array(
				'label'       => __( 'Gap Color Around Icon', 'magic-timeline-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Creates a halo around each icon badge so the line appears to break around it. Match this to the area behind your timeline.', 'magic-timeline-for-elementor' ),
				'default'     => '#FFFFFF',
				'selectors'   => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-line-gap-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'line_gap_size',
			array(
				'label'      => __( 'Gap Size', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default'    => array(
					'size' => 4,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-line-gap-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Icon badge
	 * --------------------------------------------------------------- */
	private function register_style_icon_controls() {
		$this->start_controls_section(
			'section_style_icon',
			array(
				'label' => __( 'Icon Badge', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Badge Size', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 24,
						'max' => 120,
					),
				),
				'default'    => array(
					'size' => 48,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-icon-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_font_size',
			array(
				'label'      => __( 'Icon Size', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 60,
					),
				),
				'default'    => array(
					'size' => 18,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-icon-badge svg, {{WRAPPER}} .mtl-icon-badge i' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .mtl-icon-badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'icon_background',
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .mtl-icon-badge',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#4F46E5',
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'icon_border',
				'selector' => '{{WRAPPER}} .mtl-icon-badge',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-icon-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_content_gap',
			array(
				'label'      => __( 'Spacing From Content', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'size' => 24,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-marker-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Date pill
	 * --------------------------------------------------------------- */
	private function register_style_date_controls() {
		$this->start_controls_section(
			'section_style_date',
			array(
				'label' => __( 'Date Pill', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .mtl-date-pill',
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => __( 'Text Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#475467',
				'selectors' => array(
					'{{WRAPPER}} .mtl-date-pill' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'date_background',
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .mtl-date-pill',
				'fields_options' => array(
					'color' => array( 'default' => '#F2F4F7' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'date_border',
				'selector' => '{{WRAPPER}} .mtl-date-pill',
			)
		);

		$this->add_control(
			'date_radius',
			array(
				'label'      => __( 'Border Radius', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 999,
					'right'    => 999,
					'bottom'   => 999,
					'left'     => 999,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-date-pill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'date_padding',
			array(
				'label'      => __( 'Padding', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 4,
					'right'    => 12,
					'bottom'   => 4,
					'left'     => 12,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-date-pill' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'date_margin_bottom',
			array(
				'label'      => __( 'Spacing Below', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-date-pill' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Title
	 * --------------------------------------------------------------- */
	private function register_style_title_controls() {
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#101828',
				'selectors' => array(
					'{{WRAPPER}} .mtl-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mtl-title',
			)
		);

		$this->add_responsive_control(
			'title_badge_gap',
			array(
				'label'      => __( 'Spacing From Badge', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-title-row' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_margin_bottom',
			array(
				'label'      => __( 'Spacing Below', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-title-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Status badge
	 * --------------------------------------------------------------- */
	private function register_style_badge_controls() {
		$this->start_controls_section(
			'section_style_badge',
			array(
				'label' => __( 'Status Badge', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .mtl-badge',
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => __( 'Text Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#027A48',
				'selectors' => array(
					'{{WRAPPER}} .mtl-badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'badge_background',
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .mtl-badge',
				'fields_options' => array(
					'color' => array( 'default' => '#ECFDF3' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'badge_border',
				'selector' => '{{WRAPPER}} .mtl-badge',
			)
		);

		$this->add_control(
			'badge_radius',
			array(
				'label'      => __( 'Border Radius', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 999,
					'right'    => 999,
					'bottom'   => 999,
					'left'     => 999,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'badge_padding',
			array(
				'label'      => __( 'Padding', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 2,
					'right'    => 10,
					'bottom'   => 2,
					'left'     => 10,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Description
	 * --------------------------------------------------------------- */
	private function register_style_description_controls() {
		$this->start_controls_section(
			'section_style_description',
			array(
				'label' => __( 'Description', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#475467',
				'selectors' => array(
					'{{WRAPPER}} .mtl-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .mtl-description',
			)
		);

		$this->add_control(
			'description_margin_bottom',
			array(
				'label'      => __( 'Spacing Below', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Button
	 * --------------------------------------------------------------- */
	private function register_style_button_controls() {
		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => __( 'Button', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mtl-button',
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_tab_normal',
			array( 'label' => __( 'Normal', 'magic-timeline-for-elementor' ) )
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .mtl-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_background',
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .mtl-button',
				'fields_options' => array(
					'color' => array( 'default' => '#101828' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			array( 'label' => __( 'Hover', 'magic-timeline-for-elementor' ) )
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mtl-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background_hover',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mtl-button:hover',
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'magic-timeline-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'button_border_border!' => '' ),
				'selectors' => array(
					'{{WRAPPER}} .mtl-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mtl-button',
			)
		);

		$this->add_control(
			'button_radius',
			array(
				'label'      => __( 'Border Radius', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 8,
					'right'    => 8,
					'bottom'   => 8,
					'left'     => 8,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 18,
					'bottom'   => 10,
					'left'     => 18,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_icon_gap',
			array(
				'label'      => __( 'Icon Spacing', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-button' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_margin_top',
			array(
				'label'      => __( 'Spacing Above', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-button' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * STYLE: Item spacing
	 * --------------------------------------------------------------- */
	private function register_style_spacing_controls() {
		$this->start_controls_section(
			'section_style_spacing',
			array(
				'label' => __( 'Item Spacing', 'magic-timeline-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'item_spacing',
			array(
				'label'       => __( 'Item Gap', 'magic-timeline-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 160,
					),
				),
				'default'     => array(
					'size' => 40,
					'unit' => 'px',
				),
				'description' => __( 'The gap between items — vertical in Single Column/Alternating layouts, horizontal in the Horizontal layout.', 'magic-timeline-for-elementor' ),
				'selectors'   => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-item-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'horizontal_item_width',
			array(
				'label'      => __( 'Item Width', 'magic-timeline-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 140,
						'max' => 480,
					),
				),
				'default'    => array(
					'size' => 240,
					'unit' => 'px',
				),
				'condition'  => array( 'layout_direction' => 'horizontal' ),
				'selectors'  => array(
					'{{WRAPPER}} .mtl-timeline' => '--mtl-horizontal-item-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* -----------------------------------------------------------------
	 * RENDER
	 * --------------------------------------------------------------- */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$items = ! empty( $settings['timeline_items'] ) && is_array( $settings['timeline_items'] ) ? $settings['timeline_items'] : array();

		if ( empty( $items ) ) {
			return;
		}

		$wrapper_classes = array( 'mtl-timeline' );

		if ( empty( $settings['show_line'] ) || 'yes' !== $settings['show_line'] ) {
			$wrapper_classes[] = 'mtl-line-hidden';
		}

		$wrapper_attrs = array(
			'class' => implode( ' ', $wrapper_classes ),
		);

		if ( ! empty( $settings['enable_animation'] ) && 'yes' === $settings['enable_animation'] ) {
			$wrapper_attrs['data-mtl-animate']        = 'yes';
			$wrapper_attrs['data-mtl-animate-mobile'] = ( ! empty( $settings['disable_animation_mobile'] ) && 'yes' === $settings['disable_animation_mobile'] ) ? 'no' : 'yes';
		}

		$attr_string = '';
		foreach ( $wrapper_attrs as $key => $value ) {
			$attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}

		echo '<div' . $attr_string . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		foreach ( $items as $index => $item ) {
			$this->render_item( $item, $index );
		}

		echo '</div>';
	}

	private function render_item( $item, $index ) {
		$title       = isset( $item['item_title'] ) ? $item['item_title'] : '';
		$date        = isset( $item['item_date'] ) ? $item['item_date'] : '';
		$description = isset( $item['item_description'] ) ? $item['item_description'] : '';
		$show_badge  = ! empty( $item['item_badge_show'] ) && 'yes' === $item['item_badge_show'];
		$badge_text  = isset( $item['item_badge_text'] ) ? $item['item_badge_text'] : '';
		$show_button = ! empty( $item['item_button_show'] ) && 'yes' === $item['item_button_show'];

		$animation_delay = $index * 80;
		?>
		<div class="mtl-item" style="--mtl-item-delay: <?php echo esc_attr( $animation_delay ); ?>ms;">
			<div class="mtl-item-marker">
				<span class="mtl-icon-badge">
					<?php
					if ( ! empty( $item['item_icon']['value'] ) ) {
						Icons_Manager::render_icon( $item['item_icon'], array( 'aria-hidden' => 'true' ) );
					}
					?>
				</span>
			</div>
			<div class="mtl-item-content">
				<?php if ( '' !== $date ) : ?>
					<div class="mtl-date-pill"><?php echo esc_html( $date ); ?></div>
				<?php endif; ?>

				<?php if ( '' !== $title || ( $show_badge && '' !== $badge_text ) ) : ?>
					<div class="mtl-title-row">
						<?php if ( '' !== $title ) : ?>
							<h3 class="mtl-title"><?php echo esc_html( $title ); ?></h3>
						<?php endif; ?>
						<?php if ( $show_badge && '' !== $badge_text ) : ?>
							<span class="mtl-badge"><?php echo esc_html( $badge_text ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( '' !== $description ) : ?>
					<div class="mtl-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
				<?php endif; ?>

				<?php if ( $show_button && ! empty( $item['item_button_text'] ) ) : ?>
					<?php
					$url_settings = isset( $item['item_button_url'] ) ? $item['item_button_url'] : array();
					$url          = ! empty( $url_settings['url'] ) ? $url_settings['url'] : '#';
					$link_attrs   = array( 'class="mtl-button"', 'href="' . esc_url( $url ) . '"' );

					if ( ! empty( $url_settings['is_external'] ) ) {
						$link_attrs[] = 'target="_blank"';
					}
					if ( ! empty( $url_settings['nofollow'] ) ) {
						$link_attrs[] = 'rel="nofollow"';
					}
					?>
					<a <?php echo implode( ' ', $link_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php
						if ( ! empty( $item['item_button_icon']['value'] ) ) {
							Icons_Manager::render_icon( $item['item_button_icon'], array( 'aria-hidden' => 'true' ) );
						}
						?>
						<span><?php echo esc_html( $item['item_button_text'] ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}

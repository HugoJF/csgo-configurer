<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/19/2018
 * Time: 1:42 AM
 */

namespace App\Classes;


class Breadcrumb
{
	private $text;
	private $route;
	private $isUrl;

	private $breadcrumbs = [];

	function __construct($text, $route, $isUrl = false)
	{
		$this->text = $text;
		$this->route = $route;
		$this->isUrl = $isUrl;
	}

	/**
	 * @param $text  string|array
	 * @param $route string
	 * @param $isUrl boolean
	 *
	 * @return Breadcrumb
	 */
	public function add($text, $route = null, $isUrl = false)
	{
		if (is_array($text) && $route === null) {
			if(array_key_exists('route', $text)) {
				$bc = new Breadcrumb($text['text'], $text['route']);
			} else {
				$bc = new Breadcrumb($text['text'], $text['url']);
				$isUrl = true;
			}
		} else if ($text instanceof Breadcrumb) {
			$bc = $text;
		} else {
			$bc = new Breadcrumb($text, $route, $isUrl);
		}

		$bc->isUrl = $isUrl;

		$this->breadcrumbs[] = $bc;

		return $this;
	}

	public function addUrl($text, $url)
	{
		return $this->add($text, $url, true);
	}

	public function addRoute($text, $url)
	{
		return $this->add($text, $url, false);
	}

	public function addCurrent($text)
	{
		return $this->addUrl($text, url()->current());
	}

	public function render()
	{
		$finalBreadcrumb = [];

		$thiz = [
			'text' => $this->text,
		];

		if ($this->isUrl) {
			$thiz['url'] = $this->route;
		} else {
			$thiz['route'] = $this->route;
		}

		$finalBreadcrumb[] = $thiz;

		foreach ($this->breadcrumbs as $bc) {
			$finalBreadcrumb[] = $bc->render()[0];
		}

		return $finalBreadcrumb;
	}
}
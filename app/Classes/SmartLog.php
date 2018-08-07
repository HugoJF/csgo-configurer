<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/24/2018
 * Time: 9:18 AM
 */

namespace App\Classes;


class SmartLog implements \JsonSerializable
{
	private $logs;
	private $renderOutput;

	public function __construct()
	{
		$this->logs = [];
		$this->renderOutput = '';
	}

	public function jsonSerialize()
	{
		return [
			'logs' => $this->logs,
		];
	}

	public function jsonDeserialize($data) {
		if(is_string($data)) {
			$data = json_decode($data);
		}

		if(is_array($data) || is_object($data)) {
			foreach ($data->logs as $item) {
				$log = new SmartLogMessage();

				$log->jsonDeserialize($item);

				$this->logs[] = $log;
			}
		} else {
			throw new \Exception('Deserialization data should be array or string, given: ' . gettype($data));
		}
	}

	public function addMeasure($message)
	{
		return $this->addMessage($message, true);
	}

	public function addMessage($message, $measure = false)
	{
		$log = new SmartLogMessage($message, $measure, SmartLogMessage::GENERIC_MESSAGE);

		$this->logs[] = &$log;

		return $log;
	}

	public function build()
	{
		$measures = collect($this->logs)->filter(function (SmartLogMessage $item, $key) {
			return $item->measure === true;
		});

		for ($i = 1; $i < $measures->count(); $i++) {
			$measures[$i - 1]->setDuration($measures[$i]->startTime() - $measures[$i - 1]->startTime());
		}
		$measures->get($measures->count() - 1)->setMeasure(false);
	}

	public function render()
	{
		$this->build();

		foreach ($this->logs as $log) {
			$this->renderOutput .= $log->render();
		}

		return $this->renderOutput;
	}
}
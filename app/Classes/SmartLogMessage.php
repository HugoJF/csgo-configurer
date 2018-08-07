<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/24/2018
 * Time: 9:22 AM
 */

namespace App\Classes;


class SmartLogMessage implements \JsonSerializable
{
	public const GENERIC_MESSAGE = 'generic';

	public $message;
	public $measure;
	public $type;

	private $duration;
	private $renderOutput;
	private $startTime;

	public function __construct($message = '', $measure = false, $type = SmartLogMessage::GENERIC_MESSAGE)
	{
		$this->message = $message;
		$this->measure = $measure;
		$this->type = $type;

		$this->duration = -1;
		$this->renderOutput = '';
		$this->startTime = round(microtime(true) * 1000);
	}

	public function jsonSerialize()
	{
		return [
			'message'   => $this->message,
			'measure'   => $this->measure,
			'type'      => $this->type,
			'duration'  => $this->duration,
			'startTime' => $this->startTime,
		];
	}

	public function jsonDeserialize($data)
	{
		if (is_string($data)) {
			$data = json_decode($data);
		}

		if (is_array($data) || is_object($data)) {
			$this->message = $data->message ?? '';
			$this->measure = $data->measure ?? false;
			$this->type = $data->type ?? SmartLogMessage::GENERIC_MESSAGE;
			$this->duration = $data->duration ?? -1;
			$this->startTime = $data->startTime ?? round(microtime(true) * 1000);
		} else {
			throw new \Exception('Deserialization data should be array or string, given: ' . gettype($data));
		}
	}

	public function setMeasure($measure = true)
	{
		$this->measure = $measure;

		return $this;
	}

	public function setDuration($duration)
	{
		$this->duration = $duration;

		return $this;
	}

	public function startTime()
	{
		return $this->startTime;
	}

	public function render()
	{
		$this->renderOutput .= "[{$this->type}]: {$this->message}";

		if ($this->measure) {
			$this->renderOutput .= " (took {$this->duration} ms to finish)";
		}

		$this->renderOutput .= "\n";

		return $this->renderOutput;
	}
}
<?php
namespace ISO8583\Mapper;

class AlphaNumeric extends AbstractMapper
{
	public function pack($message)
	{
		$packed = bin2hex($message);

		if ($this->getVariableLength() > 0) {
			$length = bin2hex(sprintf('%0' . $this->getVariableLength() . 'd', strlen($packed) / 2));
			$packed = $length . $packed;
		}

		return $packed;
	}

	public function unpack(&$message)
	{
		if ($this->getVariableLength() > 0) {
			$length = (int)hex2bin(substr($message, 0, $this->getVariableLength() * 2));
		} else {
			$length = $this->getLength();
		}

		$parsed = hex2bin(substr($message, $this->getVariableLength() * 2, $length * 2));
		$message = substr($message, $length * 2 + $this->getVariableLength() * 2);

		return $parsed;
	}
}
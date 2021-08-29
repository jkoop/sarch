<?php

class Hardware {
    public function __construct(Screen $screen, array $ram) {
        $this->screen = $screen;

        $this->ram = $ram;
        while (count($this->ram) < 256) {
            $this->ram[] = 0;
        }

        $this->registers = (object)[
            'a' => 0,
            'programCounter' => 32,
            'stackPointer' => 0,
            'instruction' => 0,
        ];

        // initialize the screen
        $this->screen->print('Register A:', 0, 0, 'light-gray');
        $this->screen->print('Stack Pointer:', 0, 1, 'light-gray');
        $this->screen->print('Program Counter:', 28, 0, 'light-gray');
        $this->screen->print('Instruction Reg:', 28, 1, 'light-gray');
        $this->screen->print('RAM:', 0, 3, 'light-gray');

        $this->screen->print('00', 15, 0, 'dark-gray');
        $this->screen->print('00', 15, 1, 'dark-gray');
        $this->screen->print('20', 45, 0);
        $this->screen->print('00', 45, 1, 'dark-gray');

        $this->screen->print('', 0, 4);

        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $hex = str_pad(dechex($this->ram[$i*16 + $j]), 2, '0', STR_PAD_LEFT);
                $this->screen->print($hex, null, null,
                    ($i*16+$j == $this->registers->programCounter ? 'black' :
                        ($hex == '00' ? 'dark-gray' : 'white')
                    ),
                    ($i*16+$j == $this->registers->programCounter ? 'white' : 'black')
                );
                $this->screen->print(' ', null, null, ($hex == '00' ? 'dark-gray' : 'white'));
            }

            $this->screen->printNewLine();
        }
    }

    public function ramSet(int $addr, ?int $data = null): void {
        $addr = $addr % 256;
        $data = ($data ?? $this->ram[$addr]) % 256;

        $this->ram[$addr] = $data;

        $hex = str_pad(dechex($data), 2, '0', STR_PAD_LEFT);
        $this->screen->print($hex, ($addr % 16) * 3, floor($addr / 16) + 4,
            ($addr == $this->registers->programCounter ? 'black' : 'white'),
            ($addr == $this->registers->programCounter ? 'white' : 'black')
        );
    }

    public function ramGet(int $addr): int {
        return $this->ram[$addr % 256];
    }

    public function programCounterSet($addr): void {
        $addr = $addr % 256;
        $oldAddr = $this->registers->programCounter;
        $this->registers->programCounter = $addr;

        $hex = str_pad(dechex($addr), 2, '0', STR_PAD_LEFT);
        $this->screen->print($hex, 45, 0, $hex == '00' ? 'dark-gray' : 'white');

        $this->ramSet($oldAddr);
        $this->ramSet($addr);
    }

    public function programCounterGet(): int {
        return $this->registers->programCounter;
    }

    public function instructionRegisterSet(int $instr): void {
        $instr = $instr % 256;
        $this->registers->instruction = $instr;

        $hex = str_pad(dechex($instr), 2, '0', STR_PAD_LEFT);
        $this->screen->print($hex, 45, 1, $hex == '00' ? 'dark-gray' : 'white');
    }

    public function instructionRegisterGet(): int {
        return $this->registers->instruction;
    }
}

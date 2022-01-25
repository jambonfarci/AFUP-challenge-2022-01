<?php

declare(strict_types=1);

class Ship
{
    public int $size;
    public array $coords;
    public int $hits;

    public function __construct(int $size)
    {
        $this->size = $size;
        $this->coords = [];
        $this->hits = 0;
    }
}

class Board
{
    public array $board = [];
    public Ship $s5;
    public Ship $s4;
    public Ship $s3a;
    public Ship $s3b;
    public Ship $s2;
    public int $sunkShips;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->s5 = new Ship(5);
        $this->s4 = new Ship(4);
        $this->s3a = new Ship(3);
        $this->s3b = new Ship(3);
        $this->s2 = new Ship(2);
        $this->sunkShips = 0;

        for ($y = 0; $y <= 9; $y++) {
            for ($x = 0; $x <= 9; $x++) {
                $this->board[$x][$y] = 0;
            }
        }

        foreach ([5, 4, 3, 3, 2] as $size) {
            $shipPlaced = false;

            while (!$shipPlaced) {
                $headX = random_int(0, 9);
                $headY = random_int(0, 9);
                $direction = random_int(0, 3);

                $tailX = match ($direction) {
                    0, 1 => $headX,
                    2 => $headX + $size - 1,
                    3 => $headX - $size + 1
                };

                $tailY = match ($direction) {
                    0 => $headY - $size + 1,
                    1 => $headY + $size - 1,
                    2, 3 => $headY,
                };

                if (!($tailX >= 0 && $tailX <= 9 && $tailY >= 0 && $tailY <= 9)) {
                    continue;
                }

                $xMin = min($headX, $tailX);
                $xMax = max($headX, $tailX);
                $yMin = min($headY, $tailY);
                $yMax = max($headY, $tailY);

                for ($y = $yMin - 1; $y <= $yMax + 1; $y++) {
                    if ($y < 0 || $y > 9) {
                        continue;
                    }

                    for ($x = $xMin - 1; $x <= $xMax + 1; $x++) {
                        if ($x < 0 || $x > 9) {
                            continue;
                        }

                        if ($this->board[$x][$y] !== 0) {
                            continue 3;
                        }
                    }
                }

                for ($y = $yMin; $y <= $yMax; $y++) {
                    for ($x = $xMin; $x <= $xMax; $x++) {
                        $this->board[$x][$y] = $size;

                        switch ($size) {
                            case 5:
                                $this->s5->coords[$x.$y] = 0;
                                break;
                            case 4:
                                $this->s4->coords[$x.$y] = 0;
                                break;
                            case 3:
                                if (count($this->s3a->coords) < 3) {
                                    $this->s3a->coords[$x.$y] = 0;
                                    break 2;
                                }

                                $this->s3b->coords[$x.$y] = 0;
                                break;
                            case 2:
                                $this->s2->coords[$x.$y] = 0;
                                break;
                        }
                    }
                }

                $shipPlaced = true;
            }
        }
    }

    public function hit(Ship $ship, string $coordinate): string
    {
        if ($ship->coords[$coordinate] === 0) {
            $ship->coords[$coordinate] = 1;
            $ship->hits++;

            if ($ship->hits === $ship->size) {
                $this->sunkShips++;

                if ($this->sunkShips === 5) {
                    return "won\n";
                }

                return "sunk\n";
            }

            return "hit\n";
        }

        if ($ship->hits === $ship->size) {
            return "sunk\n";
        }

        return "hit\n";
    }

    public function __toString(): string
    {
        $board = '';

        for ($y = 0; $y <= 9; $y++) {
            for ($x = 0; $x <= 9; $x++) {
                $board .= $this->board[$x][$y].' ';
            }

            $board .= "\n";
        }

        return $board;
    }
}

const COMMANDS_IN = [
    'your turn',
    'won',
    'hit',
    'miss',
    'sunk',
    'A1',
    'A2',
    'A3',
    'A4',
    'A5',
    'A6',
    'A7',
    'A8',
    'A9',
    'A10',
    'B1',
    'B2',
    'B3',
    'B4',
    'B5',
    'B6',
    'B7',
    'B8',
    'B9',
    'B10',
    'C1',
    'C2',
    'C3',
    'C4',
    'C5',
    'C6',
    'C7',
    'C8',
    'C9',
    'C10',
    'D1',
    'D2',
    'D3',
    'D4',
    'D5',
    'D6',
    'D7',
    'D8',
    'D9',
    'D10',
    'E1',
    'E2',
    'E3',
    'E4',
    'E5',
    'E6',
    'E7',
    'E8',
    'E9',
    'E10',
    'F1',
    'F2',
    'F3',
    'F4',
    'F5',
    'F6',
    'F7',
    'F8',
    'F9',
    'F10',
    'G1',
    'G2',
    'G3',
    'G4',
    'G5',
    'G6',
    'G7',
    'G8',
    'G9',
    'G10',
    'H1',
    'H2',
    'H3',
    'H4',
    'H5',
    'H6',
    'H7',
    'H8',
    'H9',
    'H10',
    'I1',
    'I2',
    'I3',
    'I4',
    'I5',
    'I6',
    'I7',
    'I8',
    'I9',
    'I10',
    'J1',
    'J2',
    'J3',
    'J4',
    'J5',
    'J6',
    'J7',
    'J8',
    'J9',
    'J10',
];

$dichotomy = [
    '44', '22', '72', '77', '27', '00', '50', '55', '05', '40', '90', '95', '45', '94', '99', '49', '04', '54', '59',
    '09', '31', '81', '86', '36', '33', '83', '88', '38', '13', '63', '68', '18', '11', '61', '16', '20', '70', '75',
    '25', '42', '92', '97', '47', '24', '74', '79', '29', '02', '52', '57', '07', '10', '60', '65', '15', '30', '80',
    '85', '35', '34', '84', '89', '39', '14', '64', '69', '19', '41', '91', '96', '46', '43', '93', '98', '48', '03',
    '53', '58', '08', '01', '51', '56', '06', '21', '71', '76', '26', '32', '82', '87', '37', '23', '73', '78', '28',
    '12', '62', '67', '17'
];

$board = new Board();
$mode = 'search';
$lastHit = '';
$plays = [];
$huntMoves = [[], [], [], []];
$nextDirection = 0;

while (true) {
    $command = fgets(STDIN);

    if ($command === false) {
        die('error could not read STDIN');
    }

    $command = trim($command);

    if (!in_array($command, COMMANDS_IN)) {
        die("error Can't understand '$command'\n");
    }

    if ($command === 'won') {
        echo "ok\n";
        break;
    }

    if ($command === 'miss') {
        echo "ok\n";
    } elseif ($command === 'hit') {
        $x = ord($lastHit[0]) - 65;
        $y = (int)$lastHit[1] - 1;

        if ($mode === 'search') {
            $mode = 'hunt';

            for ($i = 1; $i <= 4; $i++) {
                if ($y - $i >= 0) {
                    $huntMoves[0][] = $x.($y - $i);
                } else {
                    break;
                }
            }

            for ($i = 1; $i <= 4; $i++) {
                if ($y + $i <= 9) {
                    $huntMoves[1][] = $x.($y + $i);
                } else {
                    break;
                }
            }

            for ($i = 1; $i <= 4; $i++) {
                if ($x + $i <= 9) {
                    $huntMoves[2][] = ($x + $i).$y;
                } else {
                    break;
                }
            }

            for ($i = 1; $i <= 4; $i++) {
                if ($x - $i >= 0) {
                    $huntMoves[3][] = ($x - $i).$y;
                } else {
                    break;
                }
            }
        }

        echo "ok\n";
    } elseif ($command === 'sunk') {
        $mode = 'search';
        $huntMoves = [[], [], [], []];
        echo "ok\n";
    } elseif (preg_match('`^([A-J](?:[1-9]|10))$`i', $command)) {
        $x = ord($command[0]) - 65;
        $y = $command[1] - 1;

        if ($board->board[$x][$y] === 0) {
            echo "miss\n";
            continue;
        }

        switch ($board->board[$x][$y]) {
            case 5:
                echo $board->hit($board->s5, $x.$y);
                break;
            case 4:
                echo $board->hit($board->s4, $x.$y);
                break;
            case 3:
                if (in_array($x.$y, $board->s3a->coords, true)) {
                    echo $board->hit($board->s3a, $x.$y);
                    break 2;
                }

                echo $board->hit($board->s3b, $x.$y);
                break;
            case 2:
                echo $board->hit($board->s2, $x.$y);
                break;
        }
    } else { // your turn
        if ($mode === 'search') {
            $move = array_shift($dichotomy);
            $move = chr($move[0] + 65) . $move[1] + 1;

            while(in_array($move, $plays, true)) {
                $move = array_shift($dichotomy);
                $move = chr($move[0] + 65) . $move[1] + 1;
            }

            $lastHit = $move;
            $plays[] = $lastHit;
            echo $lastHit."\n";
            continue;
        }

        if (count($huntMoves[0]) > 0) {
            $nextMove = array_shift($huntMoves[0]);
            $nextDirection = 0;
        } elseif (count($huntMoves[1]) > 0) {
            $nextMove = array_shift($huntMoves[1]);
            $nextDirection = 1;
        } elseif (count($huntMoves[2]) > 0) {
            $nextMove = array_shift($huntMoves[2]);
            $nextDirection = 2;
        } else {
            $nextMove = array_shift($huntMoves[3]);
            $nextDirection = 3;
        }

        $lastHit = chr($nextMove[0] + 65) . $nextMove[1] + 1;

        while (in_array($lastHit, $plays, true)) {
            if (count($huntMoves[0]) > 0) {
                $nextMove = array_shift($huntMoves[0]);
                $nextDirection = 0;
            } elseif (count($huntMoves[1]) > 0) {
                $nextMove = array_shift($huntMoves[1]);
                $nextDirection = 1;
            } elseif (count($huntMoves[2]) > 0) {
                $nextMove = array_shift($huntMoves[2]);
                $nextDirection = 2;
            } else {
                $nextMove = array_shift($huntMoves[3]);
                $nextDirection = 3;
            }

            $lastHit = chr($nextMove[0] + 65) . $nextMove[1] + 1;
        }

        $plays[] = $lastHit;
        echo $lastHit . "\n";
    }
}

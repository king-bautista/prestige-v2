<?php

namespace App\Helpers;

define('I',20000);
class DijkstraHelper 
{
	var $visited = array();
	var $distance = array();
	var $previousNode = array();
	var $startnode =null;
	var $map = array();
	var $infiniteDistance = 0;
	var $numberOfNodes = 0;
	var $bestPath = 0;
	var $matrixWidth = 0;
	var $allowedVisit = array();
	var $instance_id = 0;
	var $screen_id = 0;
	var $with_disability = 0;
	var $i2p = array();
	
	public function __construct($points, $index_to_points, $infiniteDistance=I, $instance_id=0, $screen_id=0, $with_disability=0) {
		$ourMap = $points;
	
		$this->map = $ourMap;
		$this->infiniteDistance = $infiniteDistance;
		$this->numberOfNodes = count($ourMap);
		$this->bestPath = 0;
		$this->instance_id = $instance_id;
		$this->screen_id = $screen_id;
		$this->with_disability = $with_disability;
		$this->i2p = $index_to_points;
	}
	
	public function findShortestPath($start,$to = null) {
                $this->startnode = $start;
                for ($i=0;$i<$this->numberOfNodes;$i++) {
                if ($i == $this->startnode) {
                        $this->visited[$i] = true;
                        $this->distance[$i] = 0;
                } else {
                        $this->visited[$i] = false;
                        $this->distance[$i] = isset($this->map[$this->startnode][$i]) 
                                ? $this->map[$this->startnode][$i] 
                                : $this->infiniteDistance;
                }
                $this->previousNode[$i] = $this->startnode;
                }
                
                $maxTries = $this->numberOfNodes;
                $tries = 0;
                while (in_array(false,$this->visited,true) && $tries <= $maxTries) {                    
                        $this->bestPath = $this->findBestPath($this->distance,array_keys($this->visited,false,true));
                        if($to !== null && $this->bestPath == $to) {
                                break;
                        }
                        $this->updateDistanceAndPrevious($this->bestPath);                    
                        $this->visited[$this->bestPath] = true;
                        $tries++;
                }

	}

	public function findBestPath($ourDistance, $ourNodesLeft) {
                $bestPath = $this->infiniteDistance;
                $bestNode = 0;
                for ($i = 0,$m=count($ourNodesLeft); $i < $m; $i++) {
                        if($ourDistance[$ourNodesLeft[$i]] < $bestPath) {
                                $bestPath = $ourDistance[$ourNodesLeft[$i]];
                                $bestNode = $ourNodesLeft[$i];
                        }
                }

                return $bestNode;
	}

	public function updateDistanceAndPrevious($obp) {              
                for ($i=0;$i<$this->numberOfNodes;$i++) {
                if((isset($this->map[$obp][$i])) &&  (!($this->map[$obp][$i] == $this->infiniteDistance) || ($this->map[$obp][$i] == 0 )) && (($this->distance[$obp] + $this->map[$obp][$i]) < $this->distance[$i])) {
                        $this->distance[$i] = $this->distance[$obp] + $this->map[$obp][$i];
                        $this->previousNode[$i] = $obp;
                }
                }
	}

	public function printMap() {
		$map = $this->map;
		$placeholder = ' %' . strlen($this->infiniteDistance) .'d';
		$foo = '';
		for($i=0,$im=count($map);$i<$im;$i++) {
                        for ($k=0,$m=$im;$k<$m;$k++) {
                                $foo.= sprintf($placeholder, isset($map[$i][$k]) ? $map[$i][$k] : $this->infiniteDistance);
                        }
                        $foo.= "\n";
		}

		return $foo;
	}

	public function getResults($to = null) {
                $ourShortestPath = array();
                $foo = array();
                for ($i = 0; $i < $this->numberOfNodes; $i++) {

                        if($to !== null && $to !== $i) {
                                continue;
                        }

                        $ourShortestPath[$i] = array();
                        $endNode = null;
                        $currNode = $i;
                        $ourShortestPath[$i][] = $i;

                        while ($endNode === null || $endNode != $this->startnode) {
                                $ourShortestPath[$i][] = $this->previousNode[$currNode];
                                $endNode = $this->previousNode[$currNode];
                                $currNode = $this->previousNode[$currNode];
                        }

                        $ourShortestPath[$i] = array_reverse($ourShortestPath[$i]);

                        if ($to === null || $to === $i) {
                                if($this->distance[$i] >= $this->infiniteDistance) {
                                        //$foo .= sprintf("no route from %d to %d. \n",$this->startnode,$i);
                                } 
                                else {
                                        $ipath = array();
                                        foreach($ourShortestPath[$i] as $index) {
                                                $ipath[] = $this->i2p[$index];
                                        }
                                        
                                        $foo[] = array(
                                                'point_orig' => $this->i2p[$this->startnode],
                                                'point_dest' => $this->i2p[$i],
                                                'distance' => $this->distance[$i],
                                                'path' => implode('-',$ipath),
                                                'site_id'=>$this->instance_id,
                                                'site_screen_id'=>$this->screen_id,
                                                'with_disability'=>$this->with_disability,
                                        );
                                }

                                if ($to === $i) {
                                        break;
                                }
                        }
                }
                
                return $foo;
	}
}
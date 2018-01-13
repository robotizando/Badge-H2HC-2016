

base_size = 32.5;
base_apoio = 1.5;
h_base = 1;
h_apoio = 5;

cube([base_size, base_size, h_base]);

translate([0,0,h_base]) difference(){


	cube([base_size, base_size, h_apoio]);


	translate([base_apoio,base_apoio,0])
		cube([base_size - (base_apoio*2), base_size- (base_apoio*2), h_apoio]);


translate([0,2,0 ])
	cube([10,18,10]);


translate([2,30,0 ])
	cube([8,8,10]);

}


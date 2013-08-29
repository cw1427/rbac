<?php
require_once __DIR__."/base.php";
class LibRbacUsersTest extends PHPRBAC_Test
{
	function testAssign()
	{
		$ID1 = jf::$RBAC->Roles->Add ( "role1", "" );
		$ID11 = jf::$RBAC->Roles->Add ( "role1-1", "", $ID1 );
		$ID111 = jf::$RBAC->Roles->Add ( "role1-1-1", "", $ID11 );
		
		$ID2 = jf::$RBAC->Roles->Add ( "role2", "" );
		$ID21 = jf::$RBAC->Roles->Add ( "role2-1", "", $ID2 );
		$ID211 = jf::$RBAC->Roles->Add ( "role2-1-1", "", $ID21 );
		

		$UID = 3;
		$this->assertTrue ( jf::$RBAC->Users->assign ( $ID21, $UID ) );
		$this->assertFalse ( jf::$RBAC->Users->assign ( $ID21, $UID ) );
	}
	
	/**
	 * @depends testAssign
	 */
	function testUnassign()
	{
		$ID1 = jf::$RBAC->Roles->Add ( "role1", "" );
		$ID11 = jf::$RBAC->Roles->Add ( "role1-1", "", $ID1 );
		$ID111 = jf::$RBAC->Roles->Add ( "role1-1-1", "", $ID11 );
		
		$ID2 = jf::$RBAC->Roles->Add ( "role2", "" );
		$ID21 = jf::$RBAC->Roles->Add ( "role2-1", "", $ID2 );
		$ID211 = jf::$RBAC->Roles->Add ( "role2-1-1", "", $ID21 );
		

		$UID = 2;
		$this->assertTrue ( jf::$RBAC->Users->assign ( $ID21, $UID ) );
		$this->assertTrue ( jf::$RBAC->Users->unassign ( $ID21, $UID ) );
		$this->assertFalse ( jf::$RBAC->Users->unassign ( $ID21, $UID ) );
		$this->assertTrue ( jf::$RBAC->Users->assign ( $ID21, $UID ) );
	}
	function testallRoles()
	{
		$ID1 = jf::$RBAC->Roles->Add ( "role1", "" );
		$ID11 = jf::$RBAC->Roles->Add ( "role1-1", "", $ID1 );
		$ID111 = jf::$RBAC->Roles->Add ( "role1-1-1", "", $ID11 );
		
		$ID2 = jf::$RBAC->Roles->Add ( "role2", "" );
		$ID21 = jf::$RBAC->Roles->Add ( "role2-1", "", $ID2 );
		$ID211 = jf::$RBAC->Roles->Add ( "role2-1-1", "", $ID21 );
		
		
		$UID = 2;
		
		$this->assertEquals ( null, jf::$RBAC->Users->allRoles($UID) );
		
		jf::$RBAC->Users->assign ( $ID21, $UID );
		$res=jf::$RBAC->Users->allRoles( $UID );
		$this->assertArrayHasKey("Title", $res[0]);
		$this->assertArrayHasKey("ID", $res[0]);
		$this->assertEquals($ID21, $res[0]['ID']);
		
		
		#new
		jf::$RBAC->Users->assign ( $ID211, $UID );
		$this->assertEquals ( 2, count(jf::$RBAC->Users->allRoles ( $UID ) ));
		
	}
	function testRoleCount()
	{
		$ID1 = jf::$RBAC->Roles->Add ( "role1", "" );
		$ID11 = jf::$RBAC->Roles->Add ( "role1-1", "", $ID1 );
		$ID111 = jf::$RBAC->Roles->Add ( "role1-1-1", "", $ID11 );
		
		$ID2 = jf::$RBAC->Roles->Add ( "role2", "" );
		$ID21 = jf::$RBAC->Roles->Add ( "role2-1", "", $ID2 );
		$ID211 = jf::$RBAC->Roles->Add ( "role2-1-1", "", $ID21 );
		

		$UID = 2;
		$this->assertEquals ( 0, jf::$RBAC->Users->roleCount ( $UID ) );
		
		jf::$RBAC->Users->assign ( $ID21, $UID );
		$this->assertEquals ( 1, jf::$RBAC->Users->roleCount ( $UID ) );
		
		#same
		jf::$RBAC->Users->assign ( $ID21, $UID );
		$this->assertEquals ( 1, jf::$RBAC->Users->roleCount ( $UID ) );
		
		#new
		jf::$RBAC->Users->assign ( $ID211, $UID );
		$this->assertEquals ( 2, jf::$RBAC->Users->roleCount ( $UID ) );
		
		#to another user
		jf::$RBAC->Users->assign ( $ID211, 1 );
		$this->assertEquals ( 2, jf::$RBAC->Users->roleCount ( $UID ) );
	}
	
	/**
	 * @depends testAssign
	 */
	function testHasRole()
	{
		$ID1 = jf::$RBAC->Roles->Add ( "role1", "" );
		$ID11 = jf::$RBAC->Roles->Add ( "role1-1", "", $ID1 );
		$ID111 = jf::$RBAC->Roles->Add ( "role1-1-1", "", $ID11 );
		
		$ID2 = jf::$RBAC->Roles->Add ( "role2", "" );
		$ID21 = jf::$RBAC->Roles->Add ( "role2-1", "", $ID2 );
		$ID211 = jf::$RBAC->Roles->Add ( "role2-1-1", "", $ID21 );
		

		$UID = 2;
		jf::$RBAC->Users->assign ( $ID21, $UID );
		
		$this->assertTrue ( jf::$RBAC->Users->hasRole ( $ID21, $UID ) );
		$this->assertTrue ( jf::$RBAC->Users->hasRole ( $ID211, $UID ) );
		
		$this->assertFalse ( jf::$RBAC->Users->hasRole ( $ID2, $UID ) );
		$this->assertFalse ( jf::$RBAC->Users->hasRole ( $ID111, $UID ) );
		
		jf::$RBAC->Users->unassign ( $ID21, $UID );
		$this->assertFalse ( jf::$RBAC->Users->hasRole ( $ID21, $UID ) );
	}
	
	
	function testResetAssignments()
	{
		$ID1 = jf::$RBAC->Roles->Add ( "role1", "" );
		$ID11 = jf::$RBAC->Roles->Add ( "role1-1", "", $ID1 );
		$ID111 = jf::$RBAC->Roles->Add ( "role1-1-1", "", $ID11 );
		
		$ID2 = jf::$RBAC->Roles->Add ( "role2", "" );
		$ID21 = jf::$RBAC->Roles->Add ( "role2-1", "", $ID2 );
		$ID211 = jf::$RBAC->Roles->Add ( "role2-1-1", "", $ID21 );
		
		
		$UID = 2;
		jf::$RBAC->Users->assign ( $ID21, $UID );
		
		jf::$RBAC->Users->ResetAssignments(true);
		$this->assertEquals(1,count(jf::$RBAC->Users->allRoles(1)));
		$this->assertEquals(0,count(jf::$RBAC->Users->allRoles($UID)));
	}
}
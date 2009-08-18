<?php
class UserPageTest extends SCoreWebTestCase {
	function testUserPage() {
		$this->get_page('user');
		$this->assertTitle("Not Logged In");
		$this->assertNoText("Options");
		$this->assertNoText("More Options");

		$this->get_page('user/demo');
		$this->assertTitle("demo's Page");
		$this->assertText("Join date:");

		$this->get_page('user/MauMau');
		$this->assertTitle("No Such User");

		$this->log_in_as_user();
		// should be on the user page
		$this->assertTitle(USER_NAME+"'s Page");
		$this->assertText("Options");
		$this->assertNoText("Admin:");
		$this->log_out();

		$this->log_in_as_admin();
		// should be on the user page
		$this->assertTitle(ADMIN_NAME+"'s Page");
		$this->assertText("Options");
		$this->assertText("Admin:");
		$this->log_out();

		# FIXME: test user creation
		# FIXME: test adminifying
		# FIXME: test password reset
	}
}
?>

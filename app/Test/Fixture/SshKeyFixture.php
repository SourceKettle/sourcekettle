<?php

class SshKeyFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

	public $import = array('model' => 'SshKey');

	// Key fixtures are all one-shot generated keys for this purpose and are not in use elsewhere.
	// We will add some invalid and invalid-ish entries that would (hopefully) never make it into the database
	// to ensure that we can correctly handle junk data without breaking horribly.
    public $records = array(
        array(
            'id' => 1,
            'user_id' => 1,
            'key' => '0b73478cee542c314e014b4e4e7200670b73478cee542c314e014b4e4e720067t',
            'comment' => 'Completely invalid key',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 2,
            'user_id' => 3,
            'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
            'comment' => 'DSA key, correct, no embedded comment',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 3,
            'user_id' => 2,
            'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA== foobar@myhost',
            'comment' => 'DSA key, correct, with embedded comment',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 4,
            'user_id' => 2,
            'key' => 'AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA== foobar@myhost',
            'comment' => 'DSA key, no prefix, with embedded comment',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 5,
            'user_id' => 2,
            'key' => 'AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
            'comment' => 'DSA key, no prefix, no embedded comment',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 6,
            'user_id' => 2,
            'key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR',
            'comment' => 'RSA key, correct, no embedded comment',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 7,
            'user_id' => 2,
            'key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR foobar@myhost',
            'comment' => 'RSA key, correct, with embedded comment',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 8,
            'user_id' => 2,
            'key' => 'AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR foobar@myhost',
            'comment' => 'RSA key, no prefix, with embedded comment',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 9,
            'user_id' => 2,
            'key' => 'AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR',
            'comment' => 'RSA key, no prefix, no embedded comment',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 10,
            'user_id' => 2,
            'key' => 'AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR foobar@myhost',
            'comment' => '', //RSA key, no prefix, embedded comment, no user comment
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
        array(
            'id' => 11,
            'user_id' => 2,
			'key' => '', // No key set (fake a dodgy DB entry)
            'comment' => 'Should not be retrieved', 
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
    );
}

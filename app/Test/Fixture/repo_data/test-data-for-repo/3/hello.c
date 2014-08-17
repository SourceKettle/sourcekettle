#include <stdio.h>
int main(void) {
	printf("Hello, world!\n");
	
	// Much less overengineered than the previous version
	int i;
	for (i = 0; i < 20; i++) {
		printf("I: %d Foo: %d\n", i, i);
	}
}

#include <stdio.h>
#include <stdlib.h>
int main(void) {
	printf("Hello, world!\n");
	
	int i;
	int *foo = (int *) malloc(sizeof(int) * 20);
	for (i = 0; i < 20; i++) {
		foo[i] = i;
	}
	for (i = 0; i < 20; i++) {
		printf("I: %d Foo: %d\n", i, foo[i]);
	}
}

#include <stdlib.h>
#include <stdio.h>
#include <string.h>

int main(int argc, char *argv[])
{
	char a[500000];
	int res = 0;

	read(0, a, 500000);

	return strchr(a, 13) == NULL;
}

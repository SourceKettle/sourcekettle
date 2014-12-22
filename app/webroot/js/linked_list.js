function linkedList(lists, linkClass) {
	$(lists).sortable({
		connectWith: linkClass,
		placeholder: "ui-state-highlight"
	}).disableSelection();
}

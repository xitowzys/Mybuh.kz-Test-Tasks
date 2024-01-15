CREATE OR REPLACE FUNCTION custom_sort(arr INT[], _asc BOOLEAN DEFAULT TRUE) RETURNS INT[] AS $$
DECLARE
    gap INT := array_length(arr, 1);
    shrink DECIMAL := 1.3;
    sorted BOOLEAN := FALSE;
    i INT;
BEGIN
    LOOP
        gap := floor(gap / shrink);

        IF gap <= 1 THEN
            gap := 1;
            sorted := TRUE;
        ELSIF gap = 9 OR gap = 10 THEN
            gap := 11;
        END IF;

        i := 1;

        WHILE i + gap <= array_length(arr, 1) LOOP
            IF (_asc AND arr[i] > arr[i + gap]) OR (NOT _asc AND arr[i] < arr[i + gap]) THEN
                arr[i] := arr[i] + arr[i + gap];
                arr[i + gap] := arr[i] - arr[i + gap];
                arr[i] := arr[i] - arr[i + gap];
                sorted := FALSE;
            END IF;
            i := i + 1;
        END LOOP;

        EXIT WHEN sorted;
    END LOOP;

    RETURN arr;
END;
$$ LANGUAGE plpgsql;


SELECT custom_sort(array[1, 5, 6, 2, 8, 10, 100, 14], false)
UNION ALL
SELECT custom_sort(array[1, 5, 6, 2, 8, 10, 100, 14], true);

import { Icons, Vector } from "../../core";
import { Dom } from "../../core/dom";
import { Sequence } from "../../definition";
import {
    ComponentView,
    Placeholder,
    PlaceholderDirection,
    SequenceComponent,
} from "../component";
import { ComponentContext } from "../../component-context";
import { DefaultSequenceComponent } from "../sequence/default-sequence-component";
import { SequencePlaceIndicator } from "../../designer-extension";
import { Badges } from "../badges/badges";

const SIZE = 30;
const DEFAULT_ICON_SIZE = 22;
const FOLDER_ICON_SIZE = 18;

export class StartStopRootComponentView implements ComponentView {
    public static create(
        parent: SVGElement,
        sequence: Sequence,
        parentPlaceIndicator: SequencePlaceIndicator | null,
        context: ComponentContext
    ): StartStopRootComponentView {
        const g = Dom.svg("g", {
            class: "sqd-root-start-stop",
        });
        parent.appendChild(g);

        const sequenceComponent = DefaultSequenceComponent.create(
            g,
            {
                sequence,
                depth: 0,
                isInputConnected: true,
                isOutputConnected: true,
            },
            context
        );
        const view = sequenceComponent.view;

        const x = view.joinX - SIZE / 2;
        const endY = SIZE + view.height;

        const iconSize = parentPlaceIndicator
            ? FOLDER_ICON_SIZE
            : DEFAULT_ICON_SIZE;
        const startRectangle = createStartRectangle(
            parentPlaceIndicator ? Icons.folder : Icons.play,
            iconSize
        );

        Dom.translate(startRectangle, x, 0);
        g.appendChild(startRectangle);

        Dom.translate(view.g, 0, SIZE);

        const endCircle = createStopRectangle(
            parentPlaceIndicator ? Icons.folder : Icons.stop,
            iconSize
        );
        Dom.translate(endCircle, x, endY);
        g.appendChild(endCircle);

        let startPlaceholder: Placeholder | null = null;
        let endPlaceholder: Placeholder | null = null;
        if (parentPlaceIndicator) {
            const size = new Vector(SIZE, SIZE);
            startPlaceholder = context.services.placeholder.createForArea(
                g,
                size,
                PlaceholderDirection.out,
                parentPlaceIndicator.sequence,
                parentPlaceIndicator.index
            );
            endPlaceholder = context.services.placeholder.createForArea(
                g,
                size,
                PlaceholderDirection.out,
                parentPlaceIndicator.sequence,
                parentPlaceIndicator.index
            );

            Dom.translate(startPlaceholder.view.g, x, 0);
            Dom.translate(endPlaceholder.view.g, x, endY);
        }

        const badges = Badges.createForRoot(
            g,
            new Vector(x + SIZE, 0),
            context
        );

        return new StartStopRootComponentView(
            g,
            view.width,
            view.height + SIZE * 2,
            view.joinX,
            sequenceComponent,
            startPlaceholder,
            endPlaceholder,
            badges
        );
    }

    private constructor(
        public readonly g: SVGGElement,
        public readonly width: number,
        public readonly height: number,
        public readonly joinX: number,
        public readonly component: SequenceComponent,
        public readonly startPlaceholder: Placeholder | null,
        public readonly endPlaceholder: Placeholder | null,
        public readonly badges: Badges
    ) {}

    public destroy() {
        this.g.parentNode?.removeChild(this.g);
    }
}

function createCircle(d: string, iconSize: number): SVGGElement {
    const r = SIZE / 2;
    const circle = Dom.svg("circle", {
        class: "sqd-root-start-stop-circle",
        cx: r,
        cy: r,
        r: r,
    });

    const g = Dom.svg("g");
    g.appendChild(circle);

    const offset = (SIZE - iconSize) / 2;
    const icon = Icons.appendPath(g, "sqd-root-start-stop-icon", d, iconSize);
    Dom.translate(icon, offset, offset);
    return g;
}

function createStartRectangle(d: string, iconSize: number): SVGGElement {
    const rect = Dom.svg("rect", {
        class: "sqd-start-rect",
        height: 62,
        width: 204,
        x: -90,
        y: -35,
        rx: 5,
        ry: 5,
    });

    const g = Dom.svg("g");
    g.appendChild(rect);
    const text = Dom.svg("text", {
        x: -10,
        y: -5,
        class: "sqd-step-task-text",
    });
    text.textContent = "Start Pipe";
    g.appendChild(text);
    return g;
}

function createStopRectangle(d: string, iconSize: number): SVGGElement {
    const rect = Dom.svg("rect", {
        class: "sqd-end-rect",
        height: 30,
        width: 80,
        x: -25,
        y: 0,
        rx: 5,
        ry: 5,
    });

    const g = Dom.svg("g");
    g.appendChild(rect);
    const text = Dom.svg("text", {
        x: 8,
        y: 15,
        class: "sqd-step-task-text",
        fill: "white",
    });
    text.textContent = "END";
    g.appendChild(text);
    const flagIcon = Dom.svg("image", {
        href: "../images/flag.svg",
    });
    Dom.attrs(flagIcon, {
        height: "15",
        width: "15",
        x: -10,
        y: 8,
    });
    g.insertBefore(flagIcon, text);
    return g;
}

#!/usr/bin/env python3
"""
Bake the ticket tier illustrations into crisp, filter-free SVGs.

The sources (tools/tier-src/*.svg) get their hand-drawn wobble from an SVG
displacement filter plus a grain filter. Browsers rasterise filters at screen
resolution, so the art looked soft and pixelated. Here the same kind of wobble
is applied to the geometry itself (a smooth noise field, shared by every
shape so touching shapes stay aligned), and the filters are dropped. Result:
same drawing, sharp at any size, smaller files.

Run: python3 tools/bake-tiers.py
"""
import math
import os
import random
import re

from svgelements import SVG, Shape, Path, Close, Move, Color  # pip install svgelements

HERE = os.path.dirname(os.path.abspath(__file__))
SRC = os.path.join(HERE, 'tier-src')
OUT = os.path.join(HERE, '..', 'wp-content', 'plugins', 'tapaufest-core', 'assets', 'tiers')
AMP = 2.0      # px drift (the filter used scale 3.4)
SHADE = 0.06   # the grain filter darkened fills a touch; keep that tone
STEP = 3.0     # sample spacing along each path


def field(seed):
    rng = random.Random(seed)
    waves = [(rng.uniform(0.02, 0.05), rng.uniform(0.02, 0.05), rng.uniform(0, 6.28)) for _ in range(4)]

    def at(x, y):
        dx = sum(math.sin(a * x + b * y + p) for a, b, p in waves[:2]) * AMP / 2
        dy = sum(math.sin(b * x - a * y + p) for a, b, p in waves[2:]) * AMP / 2
        return dx, dy
    return at


def rdp(pts, eps):
    if len(pts) < 3:
        return pts
    (x1, y1), (x2, y2) = pts[0], pts[-1]
    L = math.hypot(x2 - x1, y2 - y1)
    best, idx = 0, 0
    for i in range(1, len(pts) - 1):
        x0, y0 = pts[i]
        if L < 1e-6:  # closed run: measure from the start point
            d = math.hypot(x0 - x1, y0 - y1)
        else:
            d = abs((x2 - x1) * (y1 - y0) - (x1 - x0) * (y2 - y1)) / L
        if d > best:
            best, idx = d, i
    if best > eps:
        return rdp(pts[:idx + 1], eps)[:-1] + rdp(pts[idx:], eps)
    return [pts[0], pts[-1]]


def g(v):
    s = '%.1f' % v
    return s[:-2] if s.endswith('.0') else s


def curve(pts, closed, corners=()):
    """Catmull-Rom through the points; corners keep their point (no rounding)."""
    n = len(pts)
    d = 'M%s %s' % (g(pts[0][0]), g(pts[0][1]))
    if n == 2:
        return d + 'L%s %s' % (g(pts[1][0]), g(pts[1][1]))
    for i in range(n if closed else n - 1):
        i2 = (i + 1) % n
        p1, p2 = pts[i], pts[i2]
        p0 = p1 if (i in corners or (not closed and i == 0)) else pts[(i - 1) % n]
        p3 = p2 if (i2 in corners or (not closed and i + 2 >= n)) else pts[(i + 2) % n]
        c1 = (p1[0] + (p2[0] - p0[0]) / 6, p1[1] + (p2[1] - p0[1]) / 6)
        c2 = (p2[0] - (p3[0] - p1[0]) / 6, p2[1] - (p3[1] - p1[1]) / 6)
        d += 'C%s %s %s %s %s %s' % (g(c1[0]), g(c1[1]), g(c2[0]), g(c2[1]), g(p2[0]), g(p2[1]))
    return d + ('Z' if closed else '')


def warp(path, F):
    out = []
    for sub in path.as_subpaths():
        segs = [s for s in sub.segments() if not isinstance(s, Move)]
        closed = any(isinstance(s, Close) for s in segs)
        runs, cur, prev_dir = [], [], None
        start = None
        for s in segs:
            if s.start is None or s.end is None:
                continue
            L = s.length(error=1e-3) or 0
            if L < 1e-6:
                continue
            k = max(1, int(round(L / STEP)))
            pts = [s.point(j / k) for j in range(0, k + 1)]
            pts = [(q.x, q.y) for q in pts]
            d_in = math.atan2(pts[1][1] - pts[0][1], pts[1][0] - pts[0][0])
            if cur and prev_dir is not None:
                turn = abs((d_in - prev_dir + math.pi) % (2 * math.pi) - math.pi)
                if turn > 0.45:  # a corner: start a new run
                    runs.append(cur)
                    cur = [cur[-1]]
            if not cur:
                cur = [pts[0]]
                start = start or pts[0]
            cur.extend(pts[1:])
            prev_dir = math.atan2(pts[-1][1] - pts[-2][1], pts[-1][0] - pts[-2][0])
        if cur:
            runs.append(cur)
        if not runs or sum(len(r) for r in runs) < 2:
            continue
        pts, corners = [], set()
        for r in runs:
            moved = rdp([(x + F(x, y)[0], y + F(x, y)[1]) for x, y in r], 0.18)
            if pts:
                moved = moved[1:]
            pts.extend(moved)
            corners.add(len(pts) - 1)
        corners.add(0)
        if closed and len(pts) > 2 and math.hypot(pts[0][0] - pts[-1][0], pts[0][1] - pts[-1][1]) < 0.05:
            pts.pop()
            corners.discard(len(pts))
        out.append(curve(pts, closed and len(pts) > 2, corners))
    return ''.join(out)


def col(c, shade=False):
    if c is None or c.value is None:
        return 'none'
    c = Color(c)
    if c.alpha == 0:
        return 'none'
    if shade:
        ink = (0x1E, 0x12, 0x08)
        r, gg, b = [round(v * (1 - SHADE) + i * SHADE) for v, i in zip((c.red, c.green, c.blue), ink)]
        return '#%02X%02X%02X' % (r, gg, b)
    return c.hex[:7].upper() if len(c.hex) > 7 else c.hex.upper()


def bake(name):
    src = open(os.path.join(SRC, name)).read()
    title = re.search(r'<title>(.*?)</title>', src)
    label = re.search(r'aria-label="([^"]*)"', src)
    svg = SVG.parse(os.path.join(SRC, name), reify=True)
    F = field(sum(map(ord, name)))
    parts = []
    last_fill = None
    for el in svg.elements():
        if not isinstance(el, Shape):
            continue
        if 'url(#g)' in str(el.values.get('filter', '')):
            # grain copy of the shape before it: drop it, keep its tone
            if parts and last_fill:
                parts[-1] = parts[-1].replace('fill="%s"' % last_fill, 'fill="%s"' % col(el.fill, True), 1)
            continue
        p = Path(el)
        p.reify()
        d = warp(p, F)
        if not d:
            continue
        fill, stroke = col(el.fill), col(el.stroke)
        last_fill = fill
        sw = el.stroke_width if el.stroke_width else 1
        attrs = 'd="%s" fill="%s"' % (d, fill)
        if stroke != 'none' and sw:
            attrs += ' stroke="%s" stroke-width="%s"' % (stroke, g(sw))
        parts.append('<path %s/>' % attrs)
    vb = '0 0 360 260'
    out = ('<svg xmlns="http://www.w3.org/2000/svg" viewBox="%s" role="img" aria-label="%s" '
           'stroke-linecap="round" stroke-linejoin="round"><title>%s</title>%s</svg>\n') % (
        vb, label.group(1) if label else '', title.group(1) if title else '', ''.join(parts))
    open(os.path.join(OUT, name), 'w').write(out)
    return len(src), len(out)


if __name__ == '__main__':
    os.makedirs(OUT, exist_ok=True)
    for n in sorted(os.listdir(SRC)):
        if n.endswith('.svg'):
            print(n, bake(n))
